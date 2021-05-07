#include <string.h>
#include <stdio.h>
#include "msp.h"

void transmit_string(char word[]) { 
    int i = 0;
    while(word[i] != 0)
    {
        EUSCI_A0 -> TXBUF = word[i];
        while((EUSCI_A0 -> IFG & 2) == 0)
        {

        }
        i++;


    }
}
void print_menu() {
    char word[200] = "MSP432 Menu\n\n\r1. RBG Control\n\r2. Digital Input\n\r3. Temperature Reading\n\r";
    transmit_string(word);
}
int main()
{

    EUSCI_A0 -> CTLW0 |=1;
    EUSCI_A0 -> MCTLW = 0;
    EUSCI_A0 -> CTLW0 |= 0X80;
    EUSCI_A0 -> BRW = 26;

    P1 ->SEL0 |=0X0C;
    P1 ->SEL1 &=~0X0C;

    EUSCI_A0 ->CTLW0 &=~1;

    char word[20] = "MSP432 Menu\n\r";


    while(1)
    {
        print_menu();
        char input[20];
        while(1)
        {
            int i = 0;
            if((EUSCI_A0 -> IFG & 1)!= 0)
            {
                    input[i] = EUSCI_A0 -> RXBUF;
                    EUSCI_A0 -> TXBUF = input[i];

                    while((EUSCI_A0 -> IFG & 2) == 0)
                    {

                    }
                    if(input[i] == '\r')
                    {
                        input[i] = '\0';
                        break;
                    }
                    else
                    {
                        i++;
                    }
            }

        }
        char choice = input[0];
        transmit_string(input);
        /*switch(choice)
        {
            case('1'):
                printf("case 1 selected");
                break;
            default: printf("choice is not 1, 2, or 3");
                break;
        }*/

    }
    while(1);
}