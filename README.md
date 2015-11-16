# GourdScan

被动式注入检测工具


#INSTALl
##Windows
解压之后运行 usbwebservercncn.exe即可
##LInux
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

#使用
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
