#coding=utf-8
import urllib2
import urllib
import json
import base64
import traceback
import re
from hashlib import md5
from binascii import b2a_base64 as base64_encode
from urlparse import urlparse as urlps
class isqlmap:
    def __init__(self):
        self.sqlmap_config={'tech':'BT','dbms':'mssql','user-agent':'x'}
        self.webserver="http://localhost:88/"
        self.sqlmapapi="http://127.0.0.1:8775"
		self.header_agent='x'
        try:
            self.update_config()
        except:
            print "[!] WebServer Is Not run!"
            exit()
    def update_config(self):
        config_url=self.webserver+"/api.php?type=config"
        config_load=urllib2.urlopen(config_url).read()
        self.black_ext=re.findall(r'<blackexts>(.*)</blackexts>',config_load)[0].split(",")
        self.black_domain=re.findall(r'<blackdomains>(.*)</blackdomains>',config_load)[0].split(",")
        self.white_ext=re.findall(r'<whiteext>(.*)</whiteext>',config_load)[0].split(",")                 

    def upload_hash(self,hash,key):
        api_url=self.webserver+"/api.php?type=hash&key=%s&hash=%s"%(key,hash)
        #print "Upload Hash:"+api_url
        print "[*] Task Hash "+hash
        data=urllib2.urlopen(api_url).read()
        #print "Upload hash Respone:"+data
    def test_hash(self,hash):
        api_url=self.webserver+"/api.php?type=hash_test&hash=%s"%(hash)
        print "[*] Hash Test "+hash
        data=urllib2.urlopen(api_url).read()
        #print data
        if 'is false' in data:
            return False
        if 'is true' in data:            
            return True
            
    def get_sqlmapapi(self):
        data=urllib2.urlopen(self.webserver+"/api.php?type=getapi").read()
        if data!='':   
            return data
        
    def send_sqlmap(self,url,data):
        #print url
        if(str(data))=="GET":
            sqlreq=urllib2.urlopen(url).read()
        else:
            print "[*] send to sqlmap"
            
            #print data
            req=urllib2.Request(url, data) 
            req.add_header("Content-Type","application/json")
            sqlreq = urllib2.urlopen(req).read()
        return sqlreq
    def new_task(self):       
        task=self.send_sqlmap(self.sqlmapapi+"/task/new",'GET')
        task_id=json.loads(task)
        task_id=task_id['taskid']    
        return task_id
    def send_inject(self,task_id,send_data):    
        #print send_data
        self.send_sqlmap(self.sqlmapapi+"/scan/"+task_id+"/start",send_data)
    #def send_webapi(self,)
    def send_info(self,url,data):
        data=urllib.urlencode(data)
        #print "send api data:"
        #print url
        #print data
        req=urllib2.Request(url,data)
        req=urllib2.urlopen(req)
    def check_rule(self,url):
        url_ext=urlps(url).path[-3:].lower()
        url_domain=urlps(url).netloc
        if url_ext not in self.black_ext and url_domain not in self.black_domain:  
            return True
        return False
    def fix_headers(self,headers):
        if 'Content-Length' in headers.keys():
            del(headers['Content-Length'])
        if self.header_agent!='':
            headers['User-Agent']=self.header_agent
        return headers
    def post_sqlmap(self,url,headers,body,raw_request):
        request_user_agent=headers['User-Agent']
        #userhash=headers['userhash']
        post_data={"url":url,"data":body,'user-agent':request_user_agent}
        if 'Cookie' in headers.keys():
            post_data={"url":url,"cookie":headers['Cookie'],"data":body,'user-agent':request_user_agent}
        post_data.update(self.sqlmap_config)
        post_data=json.dumps(post_data)    
        md5string="%s:%s"%(url,body)
        md5string=md5(md5string).hexdigest()
        isrun=self.test_hash(md5string)
        if isrun==True:
            taskid=self.new_task()
            send_data={'key':taskid,'request':base64_encode(raw_request),'url':base64_encode(url),'userhash':headers['userhash'],'apiserver':self.sqlmapapi}
            self.send_info(self.webserver+"/api.php?type=sqlmap",send_data)        
            
            self.send_inject(taskid,post_data)        
            self.upload_hash(md5string,taskid)
    def get_sqlmap(self,url,headers,raw_request):
        request_user_agent=headers['User-Agent']
        #userhash=headers['userhash']
        post_data={"url":url,'user-agent':request_user_agent}
        if 'Cookie' in headers.keys():
            post_data={"url":url,"cookie":headers['Cookie'],'user-agent':request_user_agent}
        post_data.update(self.sqlmap_config)
        post_data=json.dumps(post_data)    
        md5string="%s"%(url)
        md5string=md5(md5string).hexdigest()
        isrun=self.test_hash(md5string)
        if isrun==True:
            taskid=self.new_task()
            send_data={'key':taskid,'request':base64_encode(raw_request),'url':base64_encode(url),'userhash':headers['userhash'],'apiserver':self.sqlmapapi}
            self.send_info(self.webserver+"/api.php?type=sqlmap",send_data)
            
            self.send_inject(taskid,post_data) 
            self.upload_hash(md5string,taskid)
            return taskid
        return False
    def extract_request(self,url,method,headers,body):
        requests="%s %s\r\n"%(method,url)
        #print requests
        #return 0
        if self.check_rule(url)==False:
            print "[*] Request is in Black"
            return 0
        self.sqlmapapi=self.get_sqlmapapi()
        #FIX USERHASH IS NONE
        
        if 'userhash' not in str(headers.keys()).lower():
            headers.update({'userhash':'cond0r'})
        
        for key,value in headers.items():
            requests+="%s: %s\r\n"%(key,value)
        if body:
            requests+="\r\n%s"%body
        if method=='POST':
            taskid=self.post_sqlmap(url,headers,body,requests)
        if method=='GET':
            taskid=self.get_sqlmap(url,headers,requests)
        '''
        if taskid!=False:
            send_data={'key':taskid,'request':base64_encode(requests),'url':base64_encode(url),'userhash':headers['userhash']}
            self.send_info(self.webserver+"/api.php?type=sqlmap",send_data)          
        '''
        
        
        