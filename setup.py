#!/usr/bin/python

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

#------------------------------------------------------------------------------
#  Nombre: auth2db.py
#  Autor: Ezequiel Vera (ezequielvera@yahoo.com.ar)
#  Ult. Modificacion: 10/06/2010
#  Description: Parsea los archivos auth en busca de logins (smb,ssh,su,login,gdm)
#               y lo pasa a una base de datos mysql que permite listar y ordenar 
#               los resultados.
#------------------------------------------------------------------------------

# Author: Ezequiel Vera
# 
# $Revision$

__author__ = "Ezequiel Vera"
__version__ = "0.3.0"
__date__ = "2010-06-10"
__copyright__ = "Copyright (c) 2007,2008,2009,2010 Ezequiel Vera"
__license__ = "GPL"

from distutils.core import setup
#from common.version import version
from os.path import isfile, join, isdir
from sys import argv
from glob import glob
import os

longdesc = '''
Auth2db uses MySQL database to store logs, whichs allows to performe a separated 
multi-client to single DB storage, turning the tedious work of constants auditing 
into a trivial and enjoyable experience.'''

setup(
	name = "auth2db", 
	version = "0.3.0", 
	description = "Auth2db uses MySQL database to store logs, and view in the Frontend", 
	long_description = longdesc, 
	author = "Ezequiel Vera", 
	author_email = "ezequielvera@yahoo.com.ar", 
	url = "http://www.auth2db.com.ar", 
	license = "GPL", 
	platforms = "Posix", 
	scripts =	[
					'auth2db',
					'auth2db-config',
					'auth2db-alert'
				], 
	packages =	[
					'modules','scripts'
				], 
	data_files =	[
						('/etc/auth2db', 
							glob("config/*.conf")
						), 
                    				('/etc/auth2db/error.d', 
							glob("config/error.d/error.log")
						), 
						('/usr/share/doc/auth2db/www', 
							glob("www/*")
						),
						('/usr/share/doc/auth2db/sql', 
							glob("setup/*.sql")
						),
                    				('/usr/share/doc/auth2db', 
                        				["CHANGELOG","README","TODO","LICENSE"]
						),
						('/etc/init.d', 
                        				["daemon/auth2db-daemon"]
						)
					]
)

# tmp folder
#os.system("mkdir /var/lib/auth2db/tmp")
# daemon permission
#os.system("/etc/init.d/auth2db-daemon stop")
os.system("chmod 755 /etc/init.d/auth2db-daemon")
# Remove
#os.system("update-rc.d -f auth2db-daemon remove")
#os.system("update-rc.d auth2db-daemon defaults")

# Update config file
if argv[1] == "install":
	print
	print "Please do not forget to update your configuration files."
	print "They are in /etc/auth2db/."
	print "Execute 'auth2db-config' to config server and clients machines."
	print "Start auth2db daemon with '/etc/init.d/auth2db-daemon start'"
	print

