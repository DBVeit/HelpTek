// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";

import { getStorage } from "firebase/storage";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyD396No541H67WekE6mevNwj5qsqu9aUmc",
  authDomain: "helptek-tccbsi.firebaseapp.com",
  projectId: "helptek-tccbsi",
  storageBucket: "helptek-tccbsi.appspot.com",
  messagingSenderId: "933339725651",
  appId: "1:933339725651:web:5c034e340358b0ea5c6ba6",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

//inicialize Firebase Storage
const storage = getStorage(app);

// Apenas para testar a inicialização
console.log("Firebase inicializado com sucesso!");

export { app, storage };

// rodar npm install firebase
