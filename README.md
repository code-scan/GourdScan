# GourdScan

被动式注入检测工具


#INSTALl
##Windows
解压之后运行 usbwebservercncn.exe即可
##Linux
先安装好lamp,
mysql
```sql
create database pscan;
use pscan;
source pscan.sql
```

web
```sh
mv root/* /var/www/html
```
修改 ./proxy/isqlmap.py
```python
 self.webserver="http://localhost:88/"
```
改成你自己的主机地址和端口。

修改./proxy/task.py
```python
def update():
    url="http://localhost:88/api.php?type=sqlmap_update"
    urllib2.urlopen(url).read()
def api_get():
    url="http://localhost:88/api.php?type=api_get"
    data=urllib2.urlopen(url).read()
```
改成你的host地址

#配置
打开 http://localhost:88/config.php 在list里面添加sqlmapapi节点

格式为
```
http://127.0.0.1:8775 (不需要最后一个/)
```

浏览器设置代理，并且添加一个http header
```
User-Hash: youhash
```
youhash可以随意填写，主要用于分类
若不填写默认是 *cond0r*

可以在
http://localhost:88/config.php
查看你的分类，点击分类名称即可查看。

#使用
首先运行sqlmapapi，并且在config里面增加一个节点
其次开启proxy/proxy_io.py
然后运行proxy/task.py
