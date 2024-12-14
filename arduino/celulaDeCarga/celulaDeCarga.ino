#define DT1 7
#define SCK1 6

#define DT2 5
#define SCK2 4

unsigned long ReadCount();

unsigned long convert;

void setup(){
  pinMode(DT1, INPUT_PULLUP);
  pinMode(SCK1, OUTPUT);

  pinMode(DT2, INPUT_PULLUP);
  pinMode(SCK2, OUTPUT);

  Serial.begin(9600);
}

void loop(){
  int peso1 = ReadCount(DT1, SCK1);
  int peso2 = ReadCount(DT2, SCK2);

  Serial.print("Peso 1: ");
  Serial.print(peso1);
  Serial.print("    ");
  Serial.print("Peso 2: ");
  Serial.print(peso2);
  Serial.println();

  delay(2000);
}

unsigned long ReadCount(int DT, int SCK){
  unsigned long Count = 0;
  unsigned char i;

  digitalWrite(SCK,LOW);

  while(digitalRead(DT));

  for(i = 0; i < 24; i++){
    digitalWrite(SCK, HIGH);
    Count = Count << 1;
    digitalWrite(SCK, LOW);
    if(digitalRead(DT)) Count++;
  }

  digitalWrite(SCK, HIGH);
  Count = Count^0x800000;
  digitalWrite(SCK, LOW);

  return(Count);
}