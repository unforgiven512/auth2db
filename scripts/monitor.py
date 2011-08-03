#!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb
import time
import traceback
import sys
import re

# FUNCIONES generales para Auth2DB
import functions

# Import ConfigObj
sys.path.insert(1,"/usr/share/auth2db/modules/")
from configobj import ConfigObj

# Load configuration from /etc/auth2db/auth2db.conf
CONFIG_PATH = "/etc/auth2db/"
config = ConfigObj(CONFIG_PATH+'auth2db.conf')
config_filters = ConfigObj(CONFIG_PATH+"filters.conf")

# Grab configuration information from config file
CONFIG_HOST = config['CONFIG_HOST']
CONFIG_DB = config['CONFIG_DB']
CONFIG_USER = config['CONFIG_USER']
CONFIG_PASS = config['CONFIG_PASS']

def MonitorData(idview):
	'''Mueve la DATA de la tabla MAIN(log_*) a JUNK [o de JUNK a MAIN]'''

	# connect
	db = MySQLdb.connect(host=CONFIG_HOST, user=CONFIG_USER, passwd=CONFIG_PASS, db=CONFIG_DB)
	# create a cursor
	cursor = db.cursor(MySQLdb.cursors.DictCursor)


	# Si no existe la tabla JUNK_ la CREA
	if functions.check_table("monitor_"+ time.strftime('%Y_%m_%d')) == "create":

		print "CREATE TABLE JUNK..."
	    
		#// Verifica que exista la tabla de LOGS
		sql = '''
		    CREATE TABLE IF NOT EXISTS `monitor_''' + time.strftime('%Y_%m_%d') + '''` (
			`id` int(11) NOT NULL auto_increment,
			`fecha` datetime default '0000-00-00 00:00:00',
			`source` varchar(255) default NULL,
			`sourcetype` varchar(255) default NULL,
			`host` varchar(255) default NULL,
			`punct` varchar(255) default NULL,
			`tipo` varchar(255) default NULL,
			`pid` int(11) default 0,
			`action` varchar(255) default NULL,
			`usuario` varchar(255) default NULL,
			`ip` varchar(255) default NULL,
			`machine` varchar(255) default NULL,
			`detalle` text default NULL,
			`v_id` int(11) default NULL,
			`v_view` varchar(255) default NULL,
			PRIMARY KEY  (`id`)
		    ) ;
		      '''

		cursor.execute(sql)
		##print sql


	# Si la tabla JUNK_ existe, se Mueve la DATA
	if functions.check_table("monitor_"+ time.strftime('%Y_%m_%d')) == "ok" and functions.check_table("log_"+ time.strftime('%Y_%m_%d')) == "ok":
		
		# execute SQL
		sql = "SELECT * FROM view WHERE id = " + str(idview) + " AND enabled = 1"
		#print sql
		cursor.execute(sql)
		rs = cursor.fetchall()
		##print "View: " + rs[0]["view"]
		##print "Regex: " + rs[0]["regex"]

		'''
		#for record in result:
		sql = "SELECT fecha FROM log_" + time.strftime('%Y_%m_%d') 
		sql = sql + " WHERE detalle REGEXP '" + rs[0]["regex"] + "'"
		sql = sql + " AND source IN (select source from source,mm_source_filter AS mm WHERE mm.source_id = source.id AND mm.filter_id = " + str(idview) + " )"
		sql = sql + " ORDER BY fecha DESC LIMIT 1"
		'''

		rawstr = rs[0]["regex"]

		# BUSCA EL ULTIMO INSERTADO PARA ESTA VIEW
		sql = "SELECT max(id) AS id FROM monitor_" + time.strftime('%Y_%m_%d') + " WHERE v_id = " + str(idview) + " LIMIT 1"
		cursor.execute(sql)
		rs_last = cursor.fetchall()

		if rs_last[0]["id"] != None:
			last_id = rs_last[0]["id"]
		else:
			last_id = 0

		##print str(last_id)


		sql = "SELECT id, fecha, source, sourcetype, host, punct, detalle FROM log_" + time.strftime('%Y_%m_%d') + " WHERE id > " + str(last_id) + " -- LIMIT 10"
		##print sql + "\n"
		cursor.execute(sql)
		result = cursor.fetchall()


		for record in result:

			compile_obj = re.compile(rawstr,  re.IGNORECASE| re.MULTILINE)
			
			data_extract = re.search(rawstr, str(record["detalle"]),  re.IGNORECASE| re.MULTILINE)
			if data_extract:
				#print data_extract
				#print data_extract.group('p1')
				#print data_extract.group('p2')
				##print str(record["id"])
				##print str(record["source"])
				##print str(record["detalle"])
				sql = "INSERT INTO monitor_" + time.strftime('%Y_%m_%d') + " (id, fecha, source, sourcetype, host, punct, detalle, v_id, v_view) VALUES ('" + str(record["id"]) + "','" + str(record["fecha"]) + "','" + str(record["source"]) + "','" + str(record["sourcetype"]) + "','" + str(record["host"]) + "','" + str(record["punct"]) + "','" + str(record["detalle"]) + "','" + str(rs[0]["id"]) + "','" + str(rs[0]["view"]) + "') "
				##print sql + "\n"
				cursor.execute(sql)

			try:
				#print "REGEX aca -> insert a monitor_table ...."
				#print rawstr
				#print ""

				#print str(record["detalle"])
				#print data_extract.group('p2')
				#print data_extract
				bla = ""
			except:
				bla = ""

# ---------------------------
# START
# ---------------------------
def main():
	
	# Evalua si se paso el argumento -v

	try:
	    int(sys.argv[1])
	    if len(sys.argv) > 1:
		    print "Monitor: start.."
		    # FROM JUNK TO LOG
		    MonitorData(sys.argv[1])
	    else:
		    print "what?"

	except:

	    print "Err: fijate!!."



#Esta linea lanza la funcion principal si aun no esta lanzada
if __name__ =='__main__':
	try:
		main()
	except:
		# print error message re exception
		traceback.print_exc()
