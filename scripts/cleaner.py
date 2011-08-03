#!/usr/bin/python

import MySQLdb
import time
import traceback
import sys

# FUNCIONES generales para Auth2DB
import functions


CONFIG_HOST = "localhost"
CONFIG_USER = "root"
CONFIG_PASS = ""
CONFIG_DB = "auth2db"

def MoveData(t_from, t_to, idfilter):
	'''Mueve la DATA de la tabla MAIN(log_*) a JUNK [o de JUNK a MAIN]'''

	# connect
	db = MySQLdb.connect(host=CONFIG_HOST, user=CONFIG_USER, passwd=CONFIG_PASS, db=CONFIG_DB)
	# create a cursor
	cursor = db.cursor(MySQLdb.cursors.DictCursor)


	# Si no existe la tabla JUNK_ la CREA
	if functions.check_table("junk_"+ time.strftime('%Y_%m_%d')) == "create":

		print "CREATE TABLE JUNK..."
	    
		#// Verifica que exista la tabla de LOGS
		sql = '''
		    CREATE TABLE IF NOT EXISTS `junk_''' + time.strftime('%Y_%m_%d') + '''` (
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
			PRIMARY KEY  (`id`)
		    ) ;
		      '''

		cursor.execute(sql)
		##print sql


	# Si la tabla JUNK_ existe, se Mueve la DATA
	if functions.check_table("junk_"+ time.strftime('%Y_%m_%d')) == "ok"  and functions.check_table("log_"+ time.strftime('%Y_%m_%d')) == "ok":
		
		# execute SQL
		sql = "SELECT * FROM filter WHERE id = " + str(idfilter) + " AND enabled = 1"
		#print sql
		cursor.execute(sql)
		rs = cursor.fetchall()
		print "Regex: " + rs[0]["regex"]

		#for record in result:
		sql = "SELECT fecha FROM " + t_from + "_" + time.strftime('%Y_%m_%d') 
		sql = sql + " WHERE detalle REGEXP '" + rs[0]["regex"] + "'"
		sql = sql + " AND source IN (select source from source,mm_source_filter AS mm WHERE mm.source_id = source.id AND mm.filter_id = " + str(idfilter) + " )"
		sql = sql + " ORDER BY fecha DESC LIMIT 1"
		
		cursor.execute(sql)
		rs_fecha = cursor.fetchall()

		if rs_fecha:
			print str(rs_fecha[0]["fecha"])

			sql = "INSERT INTO " + t_to + "_" + time.strftime('%Y_%m_%d') + " (id, fecha, source, host, punct, detalle) (SELECT id, fecha, source, host, punct, detalle FROM " + t_from + "_" + time.strftime('%Y_%m_%d') 
			sql = sql + " WHERE detalle REGEXP '" + rs[0]["regex"] + "' AND fecha <= '" + str(rs_fecha[0]["fecha"]) + "'"
			sql = sql + " AND source IN (select source from source,mm_source_filter AS mm WHERE mm.source_id = source.id AND mm.filter_id = " + str(idfilter) + " )"
			sql = sql + ")"
			cursor.execute(sql)

			sql = "DELETE FROM " + t_from + "_" + time.strftime('%Y_%m_%d') 
			sql = sql + " WHERE detalle REGEXP '" + rs[0]["regex"] + "' AND fecha <= '" + str(rs_fecha[0]["fecha"]) + "'"
			sql = sql + " AND source IN (select source from source,mm_source_filter AS mm WHERE mm.source_id = source.id AND mm.filter_id = " + str(idfilter) + " )"
			cursor.execute(sql)
		else:
			print "Nothing to Move..."
			print ""


# ---------------------------
# START
# ---------------------------
def main():
	
	# Evalua si se paso el argumento -v

	try:
	    int(sys.argv[2])
	    if len(sys.argv) > 2 and sys.argv[1] == "main":
		    print "Clean: FROM JUNK TO **LOG**"
		    # FROM JUNK TO LOG
		    MoveData('junk', 'log', sys.argv[2])
		    
	    elif len(sys.argv) > 2 and sys.argv[1] == "junk":
		    print "Clean: FROM LOG TO **JUNK**"
		    # FROM LOG TO JUNK
		    MoveData('log', 'junk', sys.argv[2])
	    
	    else:
		    print "error en parametros..."

	except:

	    print "Err: Input[2] not integer."



#Esta linea lanza la funcion principal si aun no esta lanzada
if __name__ =='__main__':
	try:
		main()
	except:
		# print error message re exception
		traceback.print_exc()
