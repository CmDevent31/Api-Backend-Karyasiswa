// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyB6Ut6wrEDIu2HXx3IhnyYCVXAFQQjhyrQ",
  authDomain: "backend-karya.firebaseapp.com",
  databaseURL: "https://backend-karya-default-rtdb.firebaseio.com",
  projectId: "backend-karya",
  storageBucket: "backend-karya.appspot.com",
  messagingSenderId: "709491176675",
  appId: "1:709491176675:web:f8b88849c7adaf406501d6",
  measurementId: "G-2S4B9XCK6B"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);