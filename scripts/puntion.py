#!/usr/bin/python

import re

regex = r'''[a-zA-Z1-9]'''
replacement = ""
subject = "aops::o AlkjasP= m ;_d.sdfsdf_ askjh7987_as.aa"

texto = re.sub(regex, "", subject)
print texto
texto = re.sub(" ", "_", texto)
print texto