#!/bin/bash
cp /home/site/wwwroot/.azure/default /etc/nginx/sites-enabled/default
service nginx reload
