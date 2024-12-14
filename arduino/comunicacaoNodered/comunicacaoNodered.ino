// Bibliotecas
#include <ArduinoRS485.h>   // ArduinoModbus depende da lib ArduinoRS485 (instalar via gerenciador)
#include <ArduinoModbus.h>  // Lib Arduino Modbus (instalar via gerenciador)

// definicoes do escravo e da comunicao serial
#define SLAVE_ID 2              // endereco do escravo Modbus RTU
#define BAUDRATE 9600          // 57600 bps
#define UART_CONFIG SERIAL_8N1  // 8 data bits, sem paridade, 2 stop bits


const int saidaDigital = 13;
uint16_t tempRead = 0;

void setup() {
  pinMode(saidaDigital, OUTPUT);
  digitalWrite(saidaDigital, LOW);

  // configura o servidor Modbus RTU
  // Inicializa o servidor com o endereco de escravo SLAVE_ID e as configuracoes de taxa
  if (!ModbusRTUServer.begin(SLAVE_ID, BAUDRATE, UART_CONFIG)) {
    Serial.println("Falhou iniciar o Servidor Modbus RTU!");
    while (1)
      ;  // para o programa se deu erro no servidor
  }

  // associa os enderecos de registradores do servidor modbus para as entradas e saídas definidas
  // entradas digitais a partir do endereço DIGITAL_INPUT_ADDRESS
  // com esta biblioteca, apenas 4 Discrete Inputs são possíveis
  ModbusRTUServer.configureDiscreteInputs(0x0000, 4);

  // configura os registradores dos coils
  ModbusRTUServer.configureCoils(0x0020, 4);

  // configura os registradores 
  ModbusRTUServer.configureInputRegisters(0x0010, 2);

}

void loop() {
  ModbusRTUServer.poll();


  // declarar input status
  ModbusRTUServer.writeDiscreteInputs(0x0000, 1, 1);
  ModbusRTUServer.writeDiscreteInputs(0x0001, 0, 1);
  ModbusRTUServer.writeDiscreteInputs(0x0002, 1, 1);
  ModbusRTUServer.writeDiscreteInputs(0x0003, 0, 1);
  
  // declarar coil status
  // no modScan32, para o valor 0x0020, tem que acionar o registrador 0033 (porque está em decimal + 1)
  digitalWrite(saidaDigital, ModbusRTUServer.coilRead(0x0020));
  //digitalWrite(saidaDigital, ModbusRTUServer.coilRead(0x0021));
  //digitalWrite(saidaDigital, ModbusRTUServer.coilRead(0x0022));
  //digitalWrite(saidaDigital, ModbusRTUServer.coilRead(0x0023));

  // ler inputs registers
  ModbusRTUServer.writeInputRegisters(0x0010, &tempRead, 1);

  tempRead++;
  delay(10);


}