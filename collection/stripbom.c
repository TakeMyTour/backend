/* this program scripts the first three characters from a 
   file. It is intended for use where a utf-8 BOM file needs
   to be used by a program that needs just utf-8.

  The three characters are supposed to be ef,bb,bf
  but the program doesn't check that
*/
#include <stdio.h>

int main(int artc, char *argv[]) {
  char c;
  c = getchar();
  c = getchar();
  c = getchar();
  while ((c = getchar()) != EOF) printf("%c", c);
  return 0;
}
