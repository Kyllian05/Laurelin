import Client
import time
import threading
import matplotlib.pyplot as plt
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("--name",dest="name")

args = parser.parse_args()

CLIENTNUMBER = 500 #Nombre de client qui vont intéragir avec le serveur simultanément
SIMULATIONTIME = 60#durée de la simulation en seconde

startTime = time.time()

clientList : list[Client.Client] = []

clientCount = int(time.time())

for i in range(CLIENTNUMBER):#Création de tout les clients
    clientList.append(Client.Client(clientCount))
    clientCount += 1

threads : list[threading.Thread] = []

for i in range(CLIENTNUMBER):
    threads.append(threading.Thread(target=clientList[i].simulateAction,args=(SIMULATIONTIME,)))
    threads[-1].start()

for i in range(CLIENTNUMBER):
    threads[i].join()

result = Client.Client.result

allResponseTime = [i["time"] for i in result]

avgResponseTime = sum(allResponseTime)/len(allResponseTime)

print("temp de réponse moyen : "+str(avgResponseTime) + 's')
    
avgTimeForEachPage= {}

for reponse in result:
    path = Client.Client.treatPath(reponse["request"])
    if(path in avgTimeForEachPage.keys()):
        avgTimeForEachPage[path]["average"] = (avgTimeForEachPage[path]["average"]*avgTimeForEachPage[path]["coeff"] + reponse["time"])/(avgTimeForEachPage[path]["coeff"]+1)
        avgTimeForEachPage[path]["coeff"] += 1
    else:
        avgTimeForEachPage[path] = {"average" : reponse["time"],"coeff" : 1}

for page in avgTimeForEachPage.keys():
    avgTimeForEachPage[page] = avgTimeForEachPage[page]["average"]

pages = list(avgTimeForEachPage.keys())
average_times = list(avgTimeForEachPage.values())

# Create a bar chart
plt.figure(figsize=(10, 6))
plt.barh(pages, average_times, color='skyblue')
plt.xlabel('Temp moyen (seconde)')
plt.ylabel('Pages')
plt.title('Temp moyen par page avec '+str(CLIENTNUMBER)+" clients connecté en simultané pendant "+str(SIMULATIONTIME)+"s")
plt.tight_layout()

plt.savefig('assets/average_time_per_page_'+args.name+'.png', dpi=300)

avgTimePerTimestamp = {}

for reponse in result:
    timestamp = int(reponse["timestamp"]-startTime)
    if(path in avgTimePerTimestamp.keys()):
        avgTimePerTimestamp[timestamp]["average"] = (avgTimePerTimestamp[timestamp]["average"]*avgTimePerTimestamp[timestamp]["coeff"] + reponse["time"])/(avgTimePerTimestamp[timestamp]["coeff"]+1)
        avgTimePerTimestamp[timestamp]["coeff"] += 1
    else:
        avgTimePerTimestamp[timestamp] = {"average" : reponse["time"],"coeff" : 1}

for timestamp in avgTimePerTimestamp.keys():
    avgTimePerTimestamp[timestamp] = avgTimePerTimestamp[timestamp]["average"]

timestamp = list(avgTimePerTimestamp.keys())
average_times = list(avgTimePerTimestamp.values())

# Create a bar chart
plt.figure(figsize=(10, 6))
plt.plot(timestamp, average_times, color='skyblue')
plt.ylabel('Temp moyen (seconde)')
plt.xlabel('Temp écoulé')
plt.title('Temp moyen au fil du temp avec '+str(CLIENTNUMBER)+" clients connecté en simultané pendant "+str(SIMULATIONTIME)+"s")
plt.tight_layout()

plt.savefig('assets/average_time_per_timestamp_'+args.name+'.png', dpi=300)