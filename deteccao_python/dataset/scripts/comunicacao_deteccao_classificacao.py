import cv2
from ultralytics import YOLO
import socket
import time

# ---- configurações iniciais deteccao de tijolo ---- #

# Carregar o modelo YOLO entrenado
modelo_yolo = YOLO("C://Users//dell//Desktop//TCC//tijolo//projeto_detecao_objeto_2//runs//detect//train7//weights//best.pt")

# Configuração da captura de video desde a câmera
camara = cv2.VideoCapture(0)  # mudar a 1 se tiver múltiples câmeras
if not camara.isOpened():
    print("Não foi possivel abrir a câmera.")
    exit()

# ---- configurações iniciais conexão TCP com nodered --- #

# configuração de conexão TCP com Nodered
HOST = '127.0.0.1'  # IP local
PORT = 5000         # Porta do servidor


# Cria o socket TCP
with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server:
    server.bind((HOST, PORT))
    server.listen()
    print(f"Servidor TCP ouvindo em {HOST}:{PORT}")

    # Aceita a conexão de um cliente (Node-RED)
    conn, addr = server.accept()
    with conn:
        print(f"Conectado a {addr}")

        try:
            while True:
                # declara variável de status do tijolo
                statusTijolo = "nenhum_status"

                # Ler o frame da câmera
                ret, frame = camara.read()
                if not ret:
                    print("Erro ao capturar o frame.")
                    break

                # Realizar a detecção dos objetos com YOLO
                resultados_yolo = modelo_yolo.predict(frame)
                frame_anotado = resultados_yolo[0].plot()

                # Mostrar o frame anotado com as deteções
                cv2.imshow("Detecção do tijolo com YOLO", frame_anotado)
                
                # Processar cada detecção
                print("****************************************************")

                for result in resultados_yolo[0].boxes:
                    class_id = result.cls[0].item()  # ID da classe detectada
                    confidence = result.conf[0].item()  # Confiança da detecção
                    label = resultados_yolo[0].names[class_id]  # Nome da classe (tijolo bom ou ruim)
                    
                    # Verificar o tipo do tijolo
                   
                    if label == "bricks_ruin":
                        print("Tijolo ruim detectado com confiança de {:.2f}".format(confidence))
                        statusTijolo = "tijolo_ruim_detectado"
                    elif label == "bricks":
                        print("Tijolo bom detectado com confiança de {:.2f}".format(confidence))
                        statusTijolo = "tijolo_bom_detectado"
                    else:
                        print(f"Outro objeto detectado: {label} com confiança de {confidence:.2f}")
                        statusTijolo = "nenhum_tijolo_detectado"

                # Pressionar 'q' para sair do loop
                if cv2.waitKey(1) & 0xFF == ord('q'):
                    break








                # exibe status tijolo no python
                #print(f"Status do tijolo: {statusTijolo}")

                # envia status do tijolo para o nodered
                print(f"Enviando: {statusTijolo}")
                conn.sendall(statusTijolo.encode()) 

                # Presionar 'q' para sair do bucle
                if cv2.waitKey(1) & 0xFF == ord('q'):
                    break

        except Exception as e:
            print(f"Erro durante a execução: {e}")

# Liberar recursos
camara.release()
cv2.destroyAllWindows()
