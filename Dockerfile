#
# PHP School - Learn You PHP
# A revolutionary new way to learn PHP.
# Bring your imagination to life in an open learning eco-system.
# http://phpschool.io
#

FROM phpschool/workshop-manager
MAINTAINER Michael Woodward <mikeymike.mw@gmail.com>

RUN workshop-manager install learnyouphp

RUN echo "\
----------------------------------\n\
Welcome to Learn You PHP! \n\
To get started run the command: learnyouphp \n\
----------------------------------" > /etc/motd
RUN echo "cat /etc/motd" >> ~/.bashrc
