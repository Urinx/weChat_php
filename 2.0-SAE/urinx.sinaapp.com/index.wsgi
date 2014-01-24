# coding=utf-8
import sae
import sae.mail
import os,sys,cgi,urllib,urllib2,httplib,re,time,math,datetime,httplib,zipfile,pwd

from function import *

def app(environ, start_response):
    status = '200 OK'
    response_headers = [('Content-type', 'text/plain')]
    start_response(status, response_headers)
    
    shell=environ['PATH_INFO'][1:]
    
    if shell=='modules':
        return ','.join(sys.modules)
    
    elif shell=='env':
        return env(environ)
    
    elif re.search('http://|www',shell):
        return fetchurl(shell)
    
    elif shell=='mysql -h':
        return mysqlhelp()
    
    elif shell=='mysql -i':
        return mysqlinfo()
    
    #elif shell=='mail':
    #    mail()
    #    return 'success'
    
    #elif shell=='gmail':
    #    gmail()
    #    return 'success'
    
    elif shell=='test':
        conn = httplib.HTTPConnection('http://www.baidu.com:80')
        httpres = conn.getresponse()  
    	#print httpres.status
    	#print httpres.reason
    	#print httpres.read
        return httpres.read()
        
    elif eval(shell):
    	return str(eval(environ['PATH_INFO'][1:]))

application = sae.create_wsgi_app(app)
