from bs4 import BeautifulSoup
import requests
import json
import logging
from selenium.webdriver.firefox.options import Options as FirefoxOptions
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from dateutil import parser
import sqlite3
from datetime import datetime

as_of = ""
data_remaining = ""
mobile_number = "9760854852"



def getDataUsage():

    options = FirefoxOptions()
    # driver = webdriver.Firefox()
    # options.binary_location = '/usr/local/bin/geckodriver'
    # options.add_argument("--headless")
    options.headless = True
    driver = webdriver.Firefox(options=options)

    driver.get("https://www.gomo.ph/sign-in.html")

    # check if the webpage is in maintenance mode
    try:
        if driver.find_element_by_class_name("dawn-error-page__title").text == "We'll be back soon!":
            # under maintenance
            print("under maintenance! please come back again later")
            exit()
    except:
            print("Maintenance page not found, system is working as expected!")

    elem = driver.find_element_by_name("regiterNumber")
    elem.send_keys(mobile_number)
    elem.send_keys(Keys.RETURN)

    driver.implicitly_wait(10) # seconds

    # pin 
    elemPin1 = driver.find_element_by_name("input-1")
    elemPin1.send_keys("0")
    elemPin2 = driver.find_element_by_name("input-2")
    elemPin2.send_keys("0")
    elemPin3 = driver.find_element_by_name("input-3")
    elemPin3.send_keys("0")
    elemPin4 = driver.find_element_by_name("input-4")
    elemPin4.send_keys("0")
    elemPin5 = driver.find_element_by_name("input-5")
    elemPin5.send_keys("0")
    elemPin6 = driver.find_element_by_name("input-6")
    elemPin6.send_keys("0")

    # get the remaining data from the dashboard
    driver.implicitly_wait(10) # seconds
    elemDataRemaining = driver.find_element_by_class_name("data-usage__item-content-title").text
    elemDataRemainingAsOf = driver.find_element_by_class_name("data-usage__item-content-desc").text
    
    print(elemDataRemaining + " as of " + elemDataRemainingAsOf)
    # file1 = open("usage.txt","w")
    # file1.write(elemDataRemaining + " " + elemDataRemainingAsOf)
    # file1.close()

    print("data:" + elemDataRemaining)
    print("date:" + elemDataRemainingAsOf)
    

    striped_date = elemDataRemainingAsOf.strip("as of ")
    striped_date = striped_date.replace(",","")
    
    # print("formatted date:" + striped_date)
    
    dt = parser.parse(striped_date)

    global data_remaining
    global as_of
    as_of = str(dt.date())
    data_remaining = str(elemDataRemaining)

    print(as_of)

    driver.quit()

def setup():

    con = sqlite3.connect('datamonitor.db')
    cur = con.cursor()
    # Create table
    sql_create_projects_table = """ CREATE TABLE IF NOT EXISTS data_usage (
                                        mobile_number text NOT NULL,
                                        date text NOT NULL,
                                        remaining_data text
                                    ); """

    cur.execute(sql_create_projects_table)

    # Save (commit) the changes
    con.commit()

    con.close()

def saveToDatabase():

    con = sqlite3.connect('datamonitor.db')
    cur = con.cursor()

    # Insert a row of data
    cur.execute("INSERT INTO data_usage (mobile_number,date,remaining_data) VALUES (?,?,?)",(mobile_number, as_of, data_remaining))

    # Save (commit) the changes
    con.commit()

    # We can also close the connection if we are done with it.
    # Just be sure any changes have been committed or they will be lost.
    con.close()

def main():
    logging.debug('Running data monitor ...')
    print('Running data monitor ...')
    setup()
    getDataUsage()
    saveToDatabase()

if __name__ == "__main__":
    main()