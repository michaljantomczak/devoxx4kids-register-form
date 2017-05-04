FROM centos:centos7
MAINTAINER Dave Goehrig dave@dloh.org

# install pg repo
RUN rpm -i https://download.postgresql.org/pub/repos/yum/9.6/redhat/rhel-7-x86_64/pgdg-centos96-9.6-3.noarch.rpm

# install server
RUN yum install -y postgresql96-server postgresql96-contrib

# initialize DB data files
RUN su - postgres -c '/usr/pgsql-9.6/bin/initdb -D /var/lib/pgsql/data'

# set permissions to allow logins, trust the bridge, this is the default for docker YMMV
RUN echo "host    all             all             0.0.0.0/0            md5" >> /var/lib/pgsql/data/pg_hba.conf

#listen on all interfaces
RUN echo "listen_addresses='*'" >> /var/lib/pgsql/data/postgresql.conf

#expose 5432
EXPOSE 5432

#configure db
COPY init.sql /init.sql
COPY init.sh /init.sh
RUN sh init.sh

# start the database
CMD su - postgres -c '/usr/pgsql-9.6/bin/postgres -D /var/lib/pgsql/data' 
