import smbus
import time
from datetime import datetime
from smbus2 import SMBusWrapper
import MySQLdb

init_message = True
run_time = 0

#Initial Setup
# for RPI version 1, use “bus = smbus.SMBus(0)”
bus = smbus.SMBus(1)

# This is the address we setup in the Arduino Program
address = 0x04

print("Establishing connection \n")
dbName = "LightWave"
username = "pi"
password = "0000"
host = "localhost"
db = MySQLdb.connect(host,username,password,dbName)
cur = db.cursor()
################### Initial Setup ##################################

def writeNumber(value):
    bus.write_byte_data(address, 0, value)
    return -1

def readNumber():
    number = bus.read_byte(address)
    return number

def getDate():
    now = datetime.now()
    year = str(now.year)
    month = str(now.month)
    day = str(now.day)
    hour = str(now.hour)
    minutes = str(now.minute)
    date = [day,"/",month,"/",year,"-",hour,":",minutes]
    date = ''.join(date)
    
    return date

def lightControl(light_state):
    if light_state == True:
        writeNumber(int(1))
        print("Sending ON command for the light control to Arduino") 
    else:
        print("Sending OFF command for the light control to Arduino")

############### UPDATE DB ##########################################
def updateLiveInfo(light,gas,temp,hum,fire):
    print("Updating Live Info Table")
    

    cur.execute("""UPDATE Live_Info SET temp = '%s' """ %str(temp))
    db.commit()
    
    cur.execute("""UPDATE Live_Info SET hum = '%s' """ %str(hum))
    db.commit()
    
    cur.execute("""UPDATE Live_Info SET light = '%s' """ %str(light))
    db.commit()
    
    cur.execute("""UPDATE Live_Info SET gas = '%s' """ %str(gas))
    db.commit()
    
    cur.execute("""UPDATE Live_Info SET fire = '%s' """  %str(fire))
    db.commit()
    

def updateSensors(temperature,humidity,light,gas,fire):
    print("Updating Sensors Table")
    
    cur.execute("""INSERT INTO Sensors(temp,hum,light,gas,fire,date) VALUES(%s,%s,%s,%s,%s,%s)""",(str(temperature),str(humidity),str(light),str(gas),str(fire),getDate()))
    time.sleep(.5)
    db.commit()

def updateActuators(fire,gas):
    print("Updating Actuators Table")
    
    cur.execute("""UPDATE Actuators SET fire_state = '%s' """ %str(fire))
    db.commit()
    
    if int(gas) > 400:
        cur.execute("""UPDATE Actuators SET gas_state = '%s' """ %str(1))
        db.commit()
    else:
        cur.execute("""UPDATE Actuators SET gas_state = '%s' """ %str(0))
        db.commit()
        
    cur.execute("SELECT light,temp FROM Live_Info")
    actuators = cur.fetchone()
    if int(actuators[0]) < 60:
        cur.execute("""UPDATE Actuators SET light_state = '%s' """ %str(1))
        db.commit()
    else:
        cur.execute("""UPDATE Actuators SET light_state = '%s' """ %str(0))
        db.commit() 
    if int(actuators[1]) > 27:
        cur.execute("""UPDATE Actuators SET fan_state = '%s' """ %str(1))
        db.commit()
    else:
        cur.execute("""UPDATE Actuators SET fan_state = '%s' """ %str(0))
        db.commit()
########### READ ALL VALUES FROM ARDUINO #####################
def checkTemperature():
    writeNumber(int(1))
    time.sleep(.5)
    print("Temperature Received:",readNumber())
    return readNumber()

def checkHumidity():
    writeNumber(int(2))
    time.sleep(.5)
    print("Humidity Received:",readNumber())
    return readNumber()
    
def checkLight(): 
    writeNumber(int(3))
    time.sleep(.5)
    print("Light %: ",readNumber())
    return readNumber()

def checkFire():  #Sending #4 to Arduino
    writeNumber(int(4))
    time.sleep(.5)
    print("Fire: ",readNumber())
    return readNumber()

def checkGas():  #Sending #5 to Arduino
    writeNumber(int(5))
    time.sleep(.5)
    ppm_shifted_val = bus.read_byte(address)
    time.sleep(.1)
    writeNumber(int(6))
    time.sleep(.5)
    ppm_anded_val = bus.read_byte(address)
    time.sleep(.1)
    ppm_read = (ppm_shifted_val<<8) | ppm_anded_val
    print("Gas Level: ",ppm_read)
    return ppm_read

def checkShutDown():  #Checks if Shutdown was selected
    writeNumber(int(7))
    if readNumber() == int(2):
        return True
    else:
        return False
######################## Functions ###################################

while True:

    # System is ON
    if  init_message == True:
        print ("Starting up the LighWave System")
        print ("Current time:",getDate())
        init_message = False
    
    # End if button was pusshed script
    if checkShutDown():
        print("LighWave System is Idle")

    else:        
        # Get all readings     
        temp_measure = checkTemperature()
        humidity_measure = checkHumidity()
        gas_measure = checkGas()
        lightPer_measure = checkLight()
        fire_check = checkFire()

        #Update LiveInfo table     
        updateLiveInfo(lightPer_measure,gas_measure,temp_measure,humidity_measure,fire_check)
        updateActuators(fire_check,gas_measure)
        
        #Update Sensors table   
        if run_time > 30:
            updateSensors(temp_measure,humidity_measure,lightPer_measure,gas_measure,fire_check)
            run_time = 0
            
        run_time = run_time + 1
