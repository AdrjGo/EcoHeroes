import React, { useState } from 'react';
import { SafeAreaView, View, Text, TextInput, TouchableOpacity, StyleSheet, Alert } from 'react-native';

const App = () => {
  const [nombre, setNombre] = useState('');
  const [email, setEmail] = useState('');
  const [mensaje, setMensaje] = useState('');

  const handleEnviar = () => {
    if (!nombre || !email || !mensaje) {
      Alert.alert('Error', 'Por favor completa todos los campos.');
    } else {
      Alert.alert('Éxito', 'Mensaje enviado correctamente.');
      setNombre('');
      setEmail('');
      setMensaje('');
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <Text style={styles.title}>Contáctanos</Text>
      <View style={styles.form}>
        <TextInput
          style={styles.input}
          placeholder="Nombre"
          value={nombre}
          onChangeText={setNombre}
        />
        <TextInput
          style={styles.input}
          placeholder="Email"
          value={email}
          onChangeText={setEmail}
          keyboardType="email-address"
        />
        <TextInput
          style={styles.textarea}
          placeholder="Mensaje"
          value={mensaje}
          onChangeText={setMensaje}
          multiline
          numberOfLines={4}
        />
        <TouchableOpacity style={styles.button} onPress={handleEnviar}>
          <Text style={styles.buttonText}>Enviar Mensaje</Text>
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 24,
    backgroundColor: '#F4F7F6',
    justifyContent: 'center',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    marginBottom: 24,
    alignSelf: 'center',
    color: '#1a1a1a',
  },
  form: {
    backgroundColor: '#fff',
    padding: 20,
    borderRadius: 12,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowOffset: { width: 0, height: 4 },
    elevation: 4,
  },
  input: {
    borderBottomWidth: 1,
    borderBottomColor: '#ccc',
    marginBottom: 16,
    fontSize: 16,
    padding: 8,
  },
  textarea: {
    borderWidth: 1,
    borderColor: '#ccc',
    padding: 8,
    fontSize: 16,
    height: 100,
    marginBottom: 16,
    textAlignVertical: 'top',
  },
  button: {
    backgroundColor: '#28a745',
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default App;