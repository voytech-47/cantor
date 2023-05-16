import requests
import mysql.connector
from time import sleep

path = 'https://api.nbp.pl/api/exchangerates/tables/A?format=xml'
request = requests.get(path)
with open('tab.xml', 'wb') as file:
    file.write(request.content)

from xml.etree import ElementTree

tree = ElementTree.parse('tab.xml')
root = tree.getroot()

tabela = []

for el in root.findall(".//ExchangeRatesTable"):
    data = el.find('EffectiveDate').text
    number = el.find('No').text

# print(data, number)
for element in root.findall(".//Rate"):
    waluta = []
    waluta.append(element.find('Currency').text)
    waluta.append(element.find('Code').text)
    waluta.append(element.find('Mid').text)
    tabela.append(tuple(waluta))


def start(number):
    polaczenie = mysql.connector.connect(
        host='localhost',
        user='root',
        passwd=''
    )

    kursor = polaczenie.cursor()

    kursor.execute('SHOW DATABASES;')
    DBs = kursor.fetchall()

    NBDExists = False
    for baza in DBs:
        if baza[0] == 'kursy':
            NBDExists = True
            break
        if baza == DBs[-1] and NBDExists is False:
            print(f"Nie znaleziono bazy danych, tworzę...")
            kursor.execute('CREATE DATABASE kursy;')
            kursor.execute('USE kursy;')
            kursor.execute(
                'CREATE TABLE kursy (NrTabeli varchar(16), Data date, KodWaluty char(3), Kurs double(6, 4));')
            kursor.execute('CREATE TABLE kursyUSD (NrTabeli varchar(16), Data date, Kurs double(6, 4));')
            kursor.execute('CREATE TABLE kursyGBP (NrTabeli varchar(16), Data date, Kurs double(6, 4));')
            kursor.execute('CREATE TABLE kursyEUR (NrTabeli varchar(16), Data date, Kurs double(6, 4));')

    kursor.execute('USE kursy')
    kursor.execute("SELECT NrTabeli FROM kursy ORDER BY Data DESC LIMIT 1;")

    try:
        if kursor.fetchall()[0][0] == number:
            print('Zestawienie o tym numerze już istnieje w bazie')
            print('Program zakończony pomyślnie')
            try:
                sleep(1)
            except KeyboardInterrupt:
                return
        else:
            print(f"Wprowadzam najnowsze kursy do bazy...")
            for item in tabela:
                kursor.execute('USE kursy;')
                if item[1] == 'USD':
                    kursor.execute(f"INSERT INTO kursyUSD VALUES('{number}', '{data}', {item[2]});")
                if item[1] == 'GBP':
                    kursor.execute(f"INSERT INTO kursyGBP VALUES('{number}', '{data}', {item[2]});")
                if item[1] == 'EUR':
                    kursor.execute(f"INSERT INTO kursyEUR VALUES('{number}', '{data}', {item[2]});")
                kursor.execute(f"INSERT INTO kursy VALUES('{number}', '{data}', '{item[1]}', {item[2]});")

            polaczenie.commit()
            polaczenie.close()

            print('Program zakończony pomyślnie')
            try:
                sleep(1)
            except KeyboardInterrupt:
                return

    except IndexError:
        print(f"Wprowadzam najnowsze kursy do bazy...")
        for item in tabela:
            kursor.execute('USE kursy;')
            if item[1] == 'USD':
                kursor.execute(f"INSERT INTO kursyUSD VALUES('{number}', '{data}', {item[2]});")
            if item[1] == 'GBP':
                kursor.execute(f"INSERT INTO kursyGBP VALUES('{number}', '{data}', {item[2]});")
            if item[1] == 'EUR':
                kursor.execute(f"INSERT INTO kursyEUR VALUES('{number}', '{data}', {item[2]});")
            kursor.execute(f"INSERT INTO kursy VALUES('{number}', '{data}', '{item[1]}', {item[2]});")

        polaczenie.commit()
        polaczenie.close()

        print('Program zakończony pomyślnie')
        try:
            sleep(1)
        except KeyboardInterrupt:
            return


start(number)
