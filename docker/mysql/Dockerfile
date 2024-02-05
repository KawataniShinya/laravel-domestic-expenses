FROM mysql:8.0.34-debian

# GPGインストールのためのコマンド追加
RUN sed -i '/repo.mysql.com/s/^/#/' /etc/apt/sources.list.d/mysql.list
RUN apt update
RUN apt install -y curl
RUN apt clean
RUN sed -i '/repo.mysql.com/s/^#//' /etc/apt/sources.list.d/mysql.list

# GPG(MySQL公開鍵の更新)
RUN curl -sSfL https://repo.mysql.com/RPM-GPG-KEY-mysql-2023 | gpg --import
RUN gpg --batch --export "B7B3B788A8D3785C" > /etc/apt/keyrings/mysql.gpg

RUN apt update
RUN apt install -y rsyslog less vim locales
RUN sed -i -e 's/# \(ja_JP.UTF-8\)/\1/' /etc/locale.gen
RUN locale-gen
RUN update-locale LANG=ja_JP.UTF-8

ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL ja_JP.UTF-8

ENTRYPOINT ["docker-entrypoint.sh"]
EXPOSE 3306
CMD ["mysqld"]