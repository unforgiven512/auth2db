#!/usr/bin/python
# -*- coding: utf-8 -*-

#  This program is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
# 
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
# 
#  You should have received a copy of the GNU General Public License
#  along with this program; if not, write to the Free Software
#  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

#  Copyright (c) 2007,2008,2009,2010 Ezequiel Vera


__author__ = "Ezequiel Vera"
__version__ = "0.3.0"
__date__ = "2010-04-20"
__copyright__ = "Copyright (c) 2007,2008,2009,2010 Ezequiel Vera"
__license__ = "GPL"


import MySQLdb
import os
import sys
import string
import time
import datetime
import traceback

import smtplib

# FUNCIONES generales para Auth2DB
import functions

sys.path.insert(1,"/usr/share/auth2db/modules/")

from configobj import ConfigObj

#CONFIG_AUTH_PATH = "/var/log/"
CONFIG_PATH = "/etc/auth2db/"
#CONFIG_PATH_FLAG = CONFIG_PATH+"flag.d/"
CONFIG_PATH_FLAG = "/var/lib/auth2db/flag.d/"
#CONFIG_PATH_TMP = "/tmp/"
CONFIG_PATH_TMP = "/var/lib/auth2db/tmp/"

config = ConfigObj(CONFIG_PATH+'auth2db.conf')
config_filters = ConfigObj(CONFIG_PATH+"filters.conf")

# Carga en las variables el archivo config.dat
CONFIG_HOST = config['CONFIG_HOST']
CONFIG_DB = config['CONFIG_DB']
CONFIG_USER = config['CONFIG_USER']
CONFIG_PASS = config['CONFIG_PASS']

ACTIVE_ALERTS = config['ACTIVE_ALERTS']

#CONFIG_HOST = "localhost"
#CONFIG_DB = "authlog"
#CONFIG_USER = "root"
#CONFIG_PASS = ""


def send_email(subject,email,msg_body):
    
    import smtplib
    from email.MIMEText import MIMEText
    
    # connect
    db = MySQLdb.connect(host=CONFIG_HOST, user=CONFIG_USER, passwd=CONFIG_PASS, db=CONFIG_DB)
    # create a cursor
    cursor = db.cursor(MySQLdb.cursors.DictCursor)
    # execute SQL
    sql = "SELECT * from smtp_config "
    cursor.execute(sql)
    result = cursor.fetchall()
    
    for record in result:
        smtp_server = record["smtp_server"]
        port_server = record["smtp_port"]
        mail_from = record["mail_from"]
        auth_active = record["auth_active"]
        auth_user = record["auth_user"]
        auth_pass = record["auth_pass"]
    
    msg = MIMEText(msg_body)

    msg['Subject'] = str(subject)
    msg['From'] = mail_from
    msg['Reply-to'] = mail_from
    to_email = string.split(email,",") 
    # msg['To'] = str(email)
    msg['To'] = str(to_email)

    s = smtplib.SMTP()
    s.connect(smtp_server)
    
    # if required authenticate
    if auth_active == 1:
        s.login(auth_user,auth_pass)
    
    # Send the email - real from, real to, extra headers and content ...
    # s.sendmail(mail_from,str(email), msg.as_string())
    s.sendmail(mail_from, to_email, msg.as_string())
    s.close()


def alert(idalert):
    
    # connect
    db = MySQLdb.connect(host=CONFIG_HOST, user=CONFIG_USER, passwd=CONFIG_PASS, db=CONFIG_DB)

    # create a cursor
    #cursor = db.cursor()
    cursor = db.cursor(MySQLdb.cursors.DictCursor)
    # execute SQL
    sql = "SELECT * from alert_config WHERE id = " + str(idalert);
    cursor.execute(sql)
    result = cursor.fetchall()

    #print result

    new_alert = 0


    try:
	year = data_extract.group('year')
    except:
	year = time.strftime('%Y')

    try:
	month = mesreplace(data_extract.group('month'))
    except:
	month = time.strftime('%m')

    if len(month) == 1:
	    month = "0"+month

    try:
	day = data_extract.group('day')
    except:
	day = time.strftime('%d')

    if len(day) == 1:
	    day = "0"+day


    ## Verifica si existe la tabla log_
    if functions.check_table("alert_" + year +"_"+ month +"_"+ day) == "create":

	print "CREATE TABLE..."

	#// Verifica que exista la tabla de LOGS
	sql = '''
	      CREATE TABLE IF NOT EXISTS `alert_''' + year +'_'+ month +'_'+ day + '''` (
		`id` int(11) NOT NULL auto_increment,
		`id_alert_config` int(11) default NULL,
		`id_log` int(11) default NULL,
		`alert_name` varchar(255) default NULL,
		`host` varchar(255) default NULL,
		`criticality` varchar(255) default NULL,
		`severity` varchar(255) default NULL,
		`occurrences_number` int(11) default NULL,
		`detalle` text,
		`notified_time` datetime default NULL,
		PRIMARY KEY  (`id`)
	      ) ;
	      '''

	cursor.execute(sql)
	##print sql


    if functions.check_table("log_"+ time.strftime('%Y_%m_%d')) == "ok":

	for record in result:
	    #print str(record[0])+" "+record[1]
	    id = str(record["id"])
	    name = str(record["name"])
	    server = str(record["server"])
	    server_select = str(record["server_select"])
	    log_type = str(record["log_type"])
	    log_action = str(record["log_action"])
	    log_group = str(record["log_group"])
	    
	    log_contains = str(record["log_contains"])
	    log_contains_field = str(record["log_contains_field"])
	    log_contains_regex = str(record["log_contains_regex"])
	    log_contains_list = str(record["log_contains_list"])
	    
	    log_exclude = str(record["log_exclude"])
	    log_exclude_field = str(record["log_exclude_field"])
	    log_exclude_regex = str(record["log_exclude_regex"])
	    log_exclude_list = str(record["log_exclude_list"])
	    
	    criticality = record["criticality"]
	    #severity = record["severity"]
	    occurrences_number = str(record["occurrences_number"])
	    occurrences_within = str(record["occurrences_within"])
	    active_email = record["active_email"]
	    email = record["email"]
	    #occurrences_within = "3000000"

	    log_group = "host"

	    sql = "SELECT count("+ log_group +") as cantidad, max(id) as maxid,  log_"+ year +'_'+ month +'_'+ day +".* from log_"+ year +'_'+ month +'_'+ day  
	    sql = sql + " WHERE "
	    sql = sql + " fecha > ( DATE_SUB( CURDATE(), INTERVAL "+ occurrences_within +" MINUTE) ) "
	    sql = sql + " AND id > (SELECT IF (max(id_log) is not null, max(id_log), 0) as id FROM alert_"+ year +'_'+ month +'_'+ day +" WHERE id_alert_config = "+ id +")"
	    #sql = sql + " AND id > (SELECT IF (max(id_log) is not null, max(id_log), 0) as id FROM alert WHERE id_alert_config = "+ id +")"

	    if server == "1":
		sql = sql + " AND server = '"+ server_select +"' "

	    if log_contains == "1":
		sql = sql + " AND "+log_contains_field+" REGEXP '"+ log_contains_regex +"' "
	    elif log_contains == "2":
		sql = sql + " AND "+log_contains_field+" IN (SELECT DISTINCT item FROM list_item WHERE id_list IN ("+ log_contains_list +")) "
	    
	    if log_exclude == "1":
		sql = sql + " AND "+log_exclude_field+" NOT REGEXP '"+ log_exclude_regex +"' "
	    elif log_exclude == "2":
		sql = sql + " AND "+log_exclude_field+" NOT IN (SELECT DISTINCT item FROM list_item WHERE id_list IN ("+ log_exclude_list +")) "
	    
	    sql = sql + " GROUP BY "+ log_group +", host HAVING cantidad >= "+ occurrences_number +""
	    
	    ##print sql
	    #print ""
	    
	    cursor.execute(sql)
	    sub_result = cursor.fetchall()
	    
	    for sub_record in sub_result:
		id_log = str(sub_record["maxid"])
		cantidad = str(sub_record["cantidad"])
		detalle = sub_record["detalle"]
		host = sub_record["host"]
		notified_time = time.strftime('%Y-%m-%d %H:%M:%S')
		
		#sql = "INSERT INTO alert (id_alert_config, id_log, alert_name, hostname, criticality, severity, occurrences_number, detalle, notified_time) VALUES ('"+ id +"','"+ id_log +"', '"+ name +"', '"+ hostname +"', '"+ criticality +"','"+ severity +"','"+ cantidad +"','"+ detalle +"','"+ notified_time +"')"
		sql = "INSERT INTO alert_"+ year +'_'+ month +'_'+ day +" (id_alert_config, id_log, alert_name, host, criticality, occurrences_number, detalle, notified_time) VALUES ('"+ id +"','"+ id_log +"', '"+ name +"', '"+ host +"', '"+ criticality +"','"+ cantidad +"','"+ detalle +"','"+ notified_time +"')"
		#print "["+name+"]" 
		##print sql + "\n"
		cursor.execute(sql)
		insert = cursor.fetchall()
		
		# Send email if active_email = 1
		if active_email == 1:
		    msg = "\n Alert Name: '"+ name +"'\n Host: '"+ host +"'\n Criticality '"+ criticality +"'\n Occurrences Number: '"+ cantidad +"'\n Detail: '"+ detalle +"'\n Notified Time:'"+ notified_time + "'"
		    send_email("Auth2DB Alert: " + name, email, msg)
		    print "Auth2DB Alert: " + name +" | "+ email
		
		new_alert = new_alert + 1
	    

	if new_alert > 0:
	    print "New Alerts: " + str(new_alert)
	    #print ""
	    #print insert
	    #print ""


def main():
    
    try:
	int(sys.argv[1])
	if len(sys.argv) > 1:
		print "Alert: start.."
		alert(sys.argv[1])
	else:
		print "what?"

    except:

	print "Err: fijate!!."

    #if ACTIVE_ALERTS == "y" or ACTIVE_ALERTS == "Y" or 1 == 1:
    #    alert()

    

#Esta linea lanza la funcion principal si aun no esta lanzada
if __name__ =='__main__':
    try:
        main()
    except:
        # print error message re exception
        traceback.print_exc()
