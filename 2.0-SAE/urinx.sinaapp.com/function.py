# coding=utf-8
import sae
import sae.mail
import os,sys,cgi,urllib,urllib2,httplib,re,time,math,datetime,httplib,zipfile,pwd

def env(environ):
    content=''
    for param in os.environ.keys():
  		content+=param+':'+os.environ[param]+'\n'
    
    content+='='*20+'\n'
    for i in environ.keys():
        content+=i+':'+str(environ[i])+'\n'

    return content

def fetchurl(url):
    return urllib2.urlopen(url).read()

def mysqlhelp():
    return "sae.const.MYSQL_DB#数据库名\nsae.const.MYSQL_USER#用户名\nsae.const.MYSQL_PASS#密码\nsae.const.MYSQL_HOST#主库域名（可读写）\nsae.const.MYSQL_PORT#端口，类型为，请根据框架要求自行转换为int\nsae.const.MYSQL_HOST_S#从库域名（只读）"

def mysqlinfo():
    str='数据库名:'+sae.const.MYSQL_DB+'\n'+'用户名:'+sae.const.MYSQL_USER+'\n'+'密码:'+sae.const.MYSQL_PASS+'\n'+'主库域名:'+sae.const.MYSQL_HOST+'\n'+'端口'+sae.const.MYSQL_PORT+'\n'+'从库域名:'+sae.const.MYSQL_HOST_S
    return str

def mail(to,subject,body):
    #sae.mail.send_mail((to,subject,body,("smtp.qq.com", 465, "1336006643@qq.com", pw, False))
    pass

def gmail(to,subject,body):
    sae.mail.send_mail(to,subject,body,('smtp.gmail.com', 587, 'uri.lqy@gmail.com', 'password', True))
    return '发送成功！'
    
def fenci(chinese_text):
    #chinese_text = '这里填上需要分词的文本'
    _SEGMENT_BASE_URL='http://segment.sae.sina.com.cn/urlclient.php'
    payload = urllib.urlencode([('context', chinese_text),])
    args = urllib.urlencode([('word_tag', 1), ('encoding', 'UTF-8'),])
    url = _SEGMENT_BASE_URL + '?' + args
    result = urllib2.urlopen(url, payload).read()
    return result
