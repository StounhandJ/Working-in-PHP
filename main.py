# #!/usr/bin/env python3
# # -*- coding: utf-8 -*-
# import requests
# token = "42a2379eaca7daf4d7e83a8cc26e8735fe176f3da4da7eeac41204255fa36e2776ee7163bf0abf6a8cb25"
# requests.get('https://api.vk.com/method/messages.send',params={
# 	'access_token': token,
# 	'user_id': 207681600,
# 	'message': "Проверка",
# 	'random_id': 0,'v': 5.111
# 	})

import os
import sys
sys.path.append('/home/c/ci17950/myenv/lib/python3.6/site-packages/')
from flask import Flask
app = Flask(__name__)
application = app
@app.route('/', methods=['POST'])
def hello_world():
    return 'Hello, World!'
if __name__ == '__main__':
    app.run()