#include <unistd.h>
#include <stdlib.h>
#include <stdio.h>

int main(int argc, char *argv[]){

    //It needs +s
    setuid(0);
    setgid(0);
	
    char *args[] = { "/bin/bash", "/root/script.sh", "parameter1", NULL };
	
    execv("/bin/bash", args);
	
    //printf("Error executing execv.\n");
	
    return 0;
}

