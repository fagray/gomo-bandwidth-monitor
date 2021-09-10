from bs4 import BeautifulSoup
import requests
import json
import logging
from selenium.webdriver.firefox.options import Options as FirefoxOptions
from selenium import webdriver
from selenium.webdriver.common.keys import Keys

def getDataUsage():

    options = FirefoxOptions()
    options.add_argument("--headless")
    driver = webdriver.Firefox(options=options)

    driver.get("https://www.gomo.ph/sign-in.html")
    elem = driver.find_element_by_name("regiterNumber")
    elem.send_keys("9760854852")
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
    file1 = open("usage.txt","w")
    file1.write(elemDataRemaining + " " + elemDataRemainingAsOf)
    file1.close()
    
    driver.close()

    return 

def renderPage():
    return render_template('home.html')

def main():
    logging.debug('Running data monitor ...')
    print('Running data monitor ...')
    getDataUsage()

if __name__ == "__main__":
    main()