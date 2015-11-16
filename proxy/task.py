#codeing=utf-8
from time import sleep
import urllib2
def update():
    url="http://localhost:88/api.php?type=sqlmap_update"
    urllib2.urlopen(url).read()
def api_get():
    url="http://localhost:88/api.php?type=api_get"
    data=urllib2.urlopen(url).read()
count=1
while True:
    print "[*]Update Start count:%d"%count 
    update()
    print "[*]Update End count:%d"%count
    
    print "[*]Api Call Start count:%d"%count 
    api_get()
    print "[*]Api Call End count:%d\n"%count
    count+=1
    sleep(30)
    