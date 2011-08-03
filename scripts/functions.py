#!/usr/bin/python

import MySQLdb

CONFIG_HOST = "localhost"
CONFIG_USER = "root"
CONFIG_PASS = ""
CONFIG_DB = "auth2db"

def check_table(table):
    '''Verifica si la table existe'''

    # connect
    db = MySQLdb.connect(host=CONFIG_HOST, user=CONFIG_USER, passwd=CONFIG_PASS, db=CONFIG_DB)
    # create a cursor
    cursor = db.cursor(MySQLdb.cursors.DictCursor)

    # execute SQL
    sql = "SHOW TABLES LIKE '"+table+"'"
    #print sql
    
    cursor.execute(sql)
    result = cursor.fetchall()
    
    if len(result) == 0:
	return "create"
    else:
	return "ok"