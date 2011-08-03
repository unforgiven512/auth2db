#!/usr/bin/python

import MySQLdb

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
