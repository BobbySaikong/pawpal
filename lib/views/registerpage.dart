import 'dart:convert';
import 'dart:developer';
import 'package:flutter/material.dart';
import 'package:pawpal/myconfig.dart';
import 'package:pawpal/views/loginpage.dart';
import 'package:http/http.dart' as http;
import 'package:pawpal/views/main.dart';

void main(List<String> args) {
  runApp(MainApp());
}

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  bool isLoading = false;
  bool isVisible = false;

  TextEditingController nameController = TextEditingController();
  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController confirmController = TextEditingController();
  TextEditingController phoneController = TextEditingController();
  late double height, width;

  @override
  Widget build(BuildContext context) {
    height = MediaQuery.of(context).size.height;
    width = MediaQuery.of(context).size.width;
    print(width);
    if (width > 400) {
      width = 400;
    } else {
      width = width;
    }
    return Scaffold(
      appBar: AppBar(title: Text('Register')),
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Image.asset('assets/images/image.png',
              scale: 5,),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text('Please fill in the details'),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextFormField(
                  controller: nameController,
                  keyboardType: TextInputType.name,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    label: Text('Name'),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextFormField(
                  key: Key('textformfieldkey'),
                  controller: emailController,
                  keyboardType: TextInputType.emailAddress,

                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    label: Text('Email'),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextFormField(
                  obscureText: isVisible,
                  controller: passwordController,
                  keyboardType: TextInputType.visiblePassword,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    label: Text('Password'),
                    suffixIcon: IconButton(
                      onPressed: () {
                        if (isVisible) {
                          isVisible = false;
                        } else {
                          isVisible = true;
                        }
                        setState(() {});
                      },
                      icon: Icon(Icons.visibility),
                    ),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextFormField(
                  obscureText: true,
                  controller: confirmController,
                  keyboardType: TextInputType.visiblePassword,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    label: Text('Confirm Password'),

                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextFormField(
                  controller: phoneController,
                  keyboardType: TextInputType.phone,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    label: Text('Phone Number'),
                    suffix: Icon(Icons.phone),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: ElevatedButton(
                  onPressed: () {
                    log('Register button pressed.');
                    registerCheck();
                  },
                  child: Text('Register'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void registerCheck() {
    log("registercheck started.");
    String name = nameController.text.trim();
    String email = emailController.text.trim();
    String phone = phoneController.text.trim();
    String password = passwordController.text.trim();
    String confirmPass = confirmController.text.trim();

    if (name.isEmpty ||
        email.isEmpty ||
        phone.isEmpty ||
        password.isEmpty ||
        confirmPass.isEmpty) {
      printSnackBar("Please fill in all fields");
      return;
    } else if (password != confirmPass) {
      printSnackBar('Password and Confirm Password do not match');
      return;
    } else if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(email)) {
      printSnackBar('Please enter valid email');
      return;
    } else if (password.length < 6) {
      printSnackBar('Password at least 6 characters.');
      return;
    } else {
      log('registerCheck() done!');

      // showDialog(context: context, builder: (BuildContext context){
      //   return AlertDialog(
      //     title: Text('Confirm Register'),
      //     content: Text('Do you confirm to register?'),
      //     actions: ListBody(children: [
      //       Text)
      //     ],)
      //   )
      // })

      registerUser(name, email, password, phone);
    }
  }

  registerUser(String name, String email, String password, String phone) async {
    log('registerUser() starting');

    setState(() {
      isLoading = true;
    });

    printSnackBar('Registering');
    if (!mounted) return;

    await http
        .post(
          Uri.parse('${MyConfig.baseUrl}/pawpal/api/register_user.php'),
          body: {
            'name': name,
            'email': email,
            'password': password,
            'phone': phone,
          },
        )
        .then((response) {
          log(response.statusCode.toString());
          log(response.body.toString());
          if (response.statusCode == 200) {
            var jsonResponse = response.body;
            var responseArray = jsonDecode(jsonResponse);
            //log(jsonResponse);
            if (responseArray['success'] == true) {
              if (!mounted) return;

              printSnackBar('Registration successful!');

              if (isLoading) {
                setState(() {
                  isLoading = false;
                });
              }
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const LoginPage()),

                //todo: scaffold LoginPage Class(loginpage.dart)
              );
            } else {
              if (!mounted) return;
              log(response.body);
              printSnackBar('Registration failed.');
            }
          } else {
            if (!mounted) return;
            log(response.body);
            printSnackBar('Registration failed.');
          }
        })
        .timeout(
          Duration(seconds: 10),
          onTimeout: () {
            if (!mounted) return;
            printSnackBar('Request timed out. Try again');
          },
        );

    if (isLoading) {
      if (!mounted) return;
      Navigator.pop(context);
      setState(() {
        isLoading = false;
      });
    }
  }

  // Future<void> printDialog(String dialogTitle, String dialogContent,String feedback) async{
  //   return showDialog(context: context,
  //   barrierDismissible: false,
  //    builder: (BuildContext context){
  //       return AlertDialog(
  //         title: Text(dialogTitle),
  //         content: SingleChildScrollView(
  //           child: ListBody(children: [
  //             Text(dialogContent)
  //           ],),
  //         ),
  //       actions: [
  //         TextButton(onPressed: (){
  //           log('Confirm clicked');
  //           Navigator.pop(context, 'Confirm');
  //         }, child: Text('Confirm')
  //         ),
  //         TextButton(onPressed: (){
  //           log('Cancel clicked');
  //           Navigator.pop(context, 'Cancel');
  //         }, child: Text('Cancel'))
  //       ],
  //       );

  //    });
  // }

  // //get IP address(changed due to WIFI DHCP)
  // String getUrl(){
  //   return 'http://10.117.3.96';
  // }

  void printSnackBar(String message) {
    SnackBar snackbar = SnackBar(content: Text(message));
    ScaffoldMessenger.of(context).showSnackBar(snackbar);
  }
}
