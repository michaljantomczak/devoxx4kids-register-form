FROM centos:centos7

MAINTAINER http://www.centos.org
LABEL Vendor="CentOS"
LABEL License=GPLv2
LABEL Version=2.4.6-31

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm

RUN yum -y update && yum clean all
RUN yum -y install gcc gcc-c++ autoconf automake make php70w-devel php70w-pear httpd php70w php70w-pdo php70w-mysql php70w-gd php70w-xml php70w-bcmath php70w-pgsql && yum clean all
RUN pecl install Xdebug

COPY httpd.conf /etc/httpd/conf/httpd.conf
COPY php.ini /etc/php.ini
COPY xdebug.ini /etc/php.d/xdebug.ini

EXPOSE 80

# Simple startup script to avoid some issues observed with container restart 
ADD run-httpd.sh /run-httpd.sh
RUN chmod -v +x /run-httpd.sh

CMD ["/run-httpd.sh"]
