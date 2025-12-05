  import 'package:flutter/material.dart';
import 'splashscreen.dart';

void main() {
  runApp(MainApp());
}

class MainApp extends StatelessWidget {
  const MainApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        appBarTheme:AppBarTheme(
          backgroundColor: const Color.fromARGB(255, 14, 77, 71),
          foregroundColor: Colors.white,

        ),
      ),
      home: SplashScreen(),
    );
  }
}
