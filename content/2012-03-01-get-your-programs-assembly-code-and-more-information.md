---
layout: post
title: Get your programs assembly code and more information
author: Martin Thoma
date: 2012-03-01 19:40:43.000000000 +01:00
category: Code
tags: C, Assembly language
featured_image: 2012/05/assembly-thumb.png
---
I've talked today with a fellow student about some system internals and we weren't sure what actually happens. So I needed the assembly code of some example programs.

<h2>General Information</h2>
It is important to know that I will use <strong>AT&T syntax</strong> in this article!
This is AT&T Syntax: 
```text
movl %esp, %ebp
```
And this is Intel Syntax:
```text
MOVL EBP, ESP
```

<h3>Pointers</h3>
<ul>
  <li><strong>%esp</strong>: Stack pointer for top address of the stack.</li>
  <li><strong>%ebp</strong>: Stack base pointer for holding the address of the current stack frame.</li>
  <li><strong>%eax</strong>: Accumulator</li>
</ul>

The size of the eax register will always be 32 bit, regardless of the system's register size.<small><sup><a href="#ref6">[6]</a></sup></small>

<h3>$ Dollar and % Percentage signs</h3>
$i, with $i \in mathbb{N}$, is a constant and percentages mean registers.<small><sup><a href="#ref10" name="anchor10">[10]</a></sup></small><small><sup><a href="#ref10" name="anchor11">[11]</a></sup></small>


<h3>Instruction</h3>
<strong>pushl <register></strong>: To push the source operand onto the stack<small><sup><a href="#ref1" name="anchor1">[1]</a></sup></small>
<strong>movl <from>, <to></strong>: moves a long<small><sup><a href="#ref2" name="anchor2">[2]</a></sup></small>
<strong>call <function></strong>: Calls function (which might be printf, putchar, ...)
<strong>subl $16, %esp</strong>: allocate a local variable<small><sup><a href="#ref3" name="anchor3">[3]</a></sup></small>
<strong>ret</strong>: transfers control back to the place where the current function was called.<small><sup><a href="#ref3">[3]</a></sup></small>
<strong>leave</strong>: sets the stack pointer to the base frame address, effectively releasing the whole frame<small><sup><a href="#ref4" name="anchor4">[4]</a></sup></small>
<strong>andl $-16, %esp</strong>: Ands the stack with fffffff0 which effectivly aligns it on a 16 byte boundary. Access to aligned values on the stack are much faster than if they were unaligned. <small><sup><a href="#ref5" name="anchor5">[5]</a></sup></small>
<strong>jmp <target></strong>: Jump to target label.
<strong>jbe <target></strong>: Jump below or equal. I am not quite sure what is compared ... can anybody help me?
<strong>leal <command-address> <store></strong>:  Load effective address.<small><sup><a href="#ref9" name="anchor9">[9]</a></sup></small> The LEA instruction never reads memory, it only computes the address that would be read by another instruction and stores this address in its first register operand.

<h4>Suffixes</h4>
Many instructions have suffixes. This is what they mean<small><sup><a href="#ref6" name="anchor6">[6]</a></sup></small>:
<ul>
  <li>b: byte (8 bit)</li>
  <li>s: short (16 bit integer) or single (32-bit floating point)</li>
  <li>w: word (16 bit)</li>
  <li>l: long (32 bit integer or 64-bit floating point)</li>
  <li>q: quad (64 bit)</li>
  <li>t: ten bytes (80-bit floating point)</li>
</ul>


<h2>Simple example</h2>
<h3>C-Code</h3>
This program simply outputs 
```c
#include <stdio.h>

int main(void)
{
    printf("%i", 1337*42);
    return 0;
}
```

<h3>Assembly</h3>
Now I compile it and I save the assembly code:
```bash
gcc -S test.c; gcc test.c -o test
```
This gives me test.s (the assembly code) and an executable called "test".

```text
	.file	"test.c"
	.section	.rodata
.LC0:
	.string	"%i"
	.text
.globl main
	.type	main, @function
main:
	pushl	%ebp
	movl	%esp, %ebp
	andl	$-16, %esp
	subl	$16, %esp
	movl	$.LC0, %eax
	movl	$56154, 4(%esp)
	movl	%eax, (%esp)
	call	printf
	movl	$0, %eax
	leave
	ret
	.size	main, .-main
	.ident	"GCC: (Ubuntu 4.4.3-4ubuntu5) 4.4.3"
	.section	.note.GNU-stack,"",@progbits
```
This is code of the <a href="http://en.wikipedia.org/wiki/GNU_Assembler">GNU Assembler</a>. I guess other assemblers might produce other code. Could anybody please give me an example of other assemblers?

The first and most important thing you might notice is that neither "1337" nor "42" appear in the assembly code, but 56154 which is 1337*42. I didn't use any optimization options! You might also notice that constants begin with a dollar sign and registers (esp, ebp) begin with a percent sign.

The following ones are called assembly directives. They tell the assembler what to do next.
<a href="http://tigcc.ticalc.org/doc/gnuasm.html#SEC90">.file</a>, <a href="http://tigcc.ticalc.org/doc/gnuasm.html#SEC119">.section</a>, <a href="http://tigcc.ticalc.org/doc/gnuasm.html#SEC123">.size</a> and <a href="http://tigcc.ticalc.org/doc/gnuasm.html#SEC95">.ident</a> are such directives. .data might be the most well-known one and tells the assembler to store something in the data segment of the program.
.LC0 is a label for the immediately following string.
.globl indicates that the following label (in this case "main") is a global symbol.

Line 14: I'm not quite sure why you need the 4. I thought the integer size could be the reason (see <a href="http://www.cplusplus.com/doc/tutorial/variables/">variable sizes in C</a>), but as I used a string it still worked. As I used a character, it disappeared.


<h3>Further information</h3>
<code>objdump</code> gives even more information!

Archive header information: objdump -a test
```text
test:     file format elf32-i386
test
```

File header information: objdump -f test
```text
test:     file format elf32-i386
architecture: i386, flags 0x00000112:
EXEC_P, HAS_SYMS, D_PAGED
start address 0x08048330
```

Object specific file header contents: objdump -p test
```text
test:     file format elf32-i386

Program Header:
    PHDR off    0x00000034 vaddr 0x08048034 paddr 0x08048034 align 2**2
         filesz 0x00000100 memsz 0x00000100 flags r-x
  INTERP off    0x00000134 vaddr 0x08048134 paddr 0x08048134 align 2**0
         filesz 0x00000013 memsz 0x00000013 flags r--
    LOAD off    0x00000000 vaddr 0x08048000 paddr 0x08048000 align 2**12
         filesz 0x000004d8 memsz 0x000004d8 flags r-x
    LOAD off    0x00000f0c vaddr 0x08049f0c paddr 0x08049f0c align 2**12
         filesz 0x00000108 memsz 0x00000110 flags rw-
 DYNAMIC off    0x00000f20 vaddr 0x08049f20 paddr 0x08049f20 align 2**2
         filesz 0x000000d0 memsz 0x000000d0 flags rw-
    NOTE off    0x00000148 vaddr 0x08048148 paddr 0x08048148 align 2**2
         filesz 0x00000044 memsz 0x00000044 flags r--
   STACK off    0x00000000 vaddr 0x00000000 paddr 0x00000000 align 2**2
         filesz 0x00000000 memsz 0x00000000 flags rw-
   RELRO off    0x00000f0c vaddr 0x08049f0c paddr 0x08049f0c align 2**0
         filesz 0x000000f4 memsz 0x000000f4 flags r--

Dynamic Section:
  NEEDED               libc.so.6
  INIT                 0x080482bc
  FINI                 0x080484ac
  HASH                 0x0804818c
  GNU_HASH             0x080481b4
  STRTAB               0x08048224
  SYMTAB               0x080481d4
  STRSZ                0x0000004c
  SYMENT               0x00000010
  DEBUG                0x00000000
  PLTGOT               0x08049ff4
  PLTRELSZ             0x00000018
  PLTREL               0x00000011
  JMPREL               0x080482a4
  REL                  0x0804829c
  RELSZ                0x00000008
  RELENT               0x00000008
  VERNEED              0x0804827c
  VERNEEDNUM           0x00000001
  VERSYM               0x08048270

Version References:
  required from libc.so.6:
    0x0d696910 0x00 02 GLIBC_2.0
```

Display the contents of the section headers: objdump -h test
```text
test:     file format elf32-i386

Sections:
Idx Name          Size      VMA       LMA       File off  Algn
  0 .interp       00000013  08048134  08048134  00000134  2**0
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  1 .note.ABI-tag 00000020  08048148  08048148  00000148  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  2 .note.gnu.build-id 00000024  08048168  08048168  00000168  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  3 .hash         00000028  0804818c  0804818c  0000018c  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  4 .gnu.hash     00000020  080481b4  080481b4  000001b4  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  5 .dynsym       00000050  080481d4  080481d4  000001d4  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  6 .dynstr       0000004c  08048224  08048224  00000224  2**0
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  7 .gnu.version  0000000a  08048270  08048270  00000270  2**1
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  8 .gnu.version_r 00000020  0804827c  0804827c  0000027c  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
  9 .rel.dyn      00000008  0804829c  0804829c  0000029c  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
 10 .rel.plt      00000018  080482a4  080482a4  000002a4  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
 11 .init         00000030  080482bc  080482bc  000002bc  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, CODE
 12 .plt          00000040  080482ec  080482ec  000002ec  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, CODE
 13 .text         0000017c  08048330  08048330  00000330  2**4
                  CONTENTS, ALLOC, LOAD, READONLY, CODE
 14 .fini         0000001c  080484ac  080484ac  000004ac  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, CODE
 15 .rodata       0000000b  080484c8  080484c8  000004c8  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
 16 .eh_frame     00000004  080484d4  080484d4  000004d4  2**2
                  CONTENTS, ALLOC, LOAD, READONLY, DATA
 17 .ctors        00000008  08049f0c  08049f0c  00000f0c  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 18 .dtors        00000008  08049f14  08049f14  00000f14  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 19 .jcr          00000004  08049f1c  08049f1c  00000f1c  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 20 .dynamic      000000d0  08049f20  08049f20  00000f20  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 21 .got          00000004  08049ff0  08049ff0  00000ff0  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 22 .got.plt      00000018  08049ff4  08049ff4  00000ff4  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 23 .data         00000008  0804a00c  0804a00c  0000100c  2**2
                  CONTENTS, ALLOC, LOAD, DATA
 24 .bss          00000008  0804a014  0804a014  00001014  2**2
                  ALLOC
 25 .comment      00000023  00000000  00000000  00001014  2**0
                  CONTENTS, READONLY
```

Display DWARF info in the file: objdump --dwarf test
```text
test:     file format elf32-i386

Contents of the .eh_frame section:

00000000 ZERO terminator
```
By the way, <a href="http://en.wikipedia.org/wiki/Executable_and_Linkable_Format">ELF</a> is an executable file format and <a href="http://en.wikipedia.org/wiki/DWARF">DWARF</a> is a debugging file format. I guess they had to think quite long to find this <a href="http://en.wikipedia.org/wiki/Backronym">backronym</a>.

<h2>Fibonacci</h2>
<h3>C-Code</h3>
This is the most simple version of Fibonacci I could find:<small><sup><a href="#ref7" name="anchor7">[7]</a></sup></small>
```c
#include <stdio.h>

unsigned int fib(unsigned int n)
{
	return n < 2 ? n : fib(n-1) + fib(n-2);
}

int main(void)
{
	printf("%i", fib(13));
	return 0;
}
```

<h3>Assembly</h3>
```text
	.file	"test.c"
	.text
.globl fib
	.type	fib, @function
fib:
	pushl	%ebp
	movl	%esp, %ebp
	pushl	%ebx
	subl	$20, %esp
	cmpl	$1, 8(%ebp)
	jbe	.L2
	movl	8(%ebp), %eax
	subl	$1, %eax
	movl	%eax, (%esp)
	call	fib
	movl	%eax, %ebx
	movl	8(%ebp), %eax
	subl	$2, %eax
	movl	%eax, (%esp)
	call	fib
	leal	(%ebx,%eax), %eax
	jmp	.L3
.L2:
	movl	8(%ebp), %eax
.L3:
	addl	$20, %esp
	popl	%ebx
	popl	%ebp
	ret
	.size	fib, .-fib
	.section	.rodata
.LC0:
	.string	"%i"
	.text
.globl main
	.type	main, @function
main:
	pushl	%ebp
	movl	%esp, %ebp
	andl	$-16, %esp
	subl	$16, %esp
	movl	$13, (%esp)
	call	fib
	movl	$.LC0, %edx
	movl	%eax, 4(%esp)
	movl	%edx, (%esp)
	call	printf
	movl	$0, %eax
	leave
	ret
	.size	main, .-main
	.ident	"GCC: (Ubuntu 4.4.3-4ubuntu5) 4.4.3"
	.section	.note.GNU-stack,"",@progbits
```

<h2>References</h2>
<ol>
  <li><a name="ref1" href="#anchor1">&uarr;</a>: <a href="http://www.cs.auckland.ac.nz/references/macvax/op-codes/Instructions/pushl.html">PUSHL Instruction</a>. The University of Auckland, Department of Computer Science.</li>
  <li><a name="ref2" href="#anchor2">&uarr;</a>: <a href="http://www.cse.nd.edu/~dthain/courses/cse40243/fall2008/ia32-intro.html">IA-32 Assembly for Compiler Writers</a>. Douglas Thain, Associate Professor, University of Notre Dame, Department of Computer Science and Engineering.</li>
  <li><a name="ref3" href="#anchor3">&uarr;</a>: <a href="http://linuxgazette.net/issue94/ramankutty.html">From C To Assembly Language </a>. Hiran Ramankutty, Linux Gazett, Issue 94.</li>
  <li><a name="ref4" href="#anchor4">&uarr;</a>: <a href="http://stackoverflow.com/questions/5474355/about-leave-in-x86-assembly">About leave in x86 assembly</a>. zneak, Stackoverflow.</li>
  <li><a name="ref5" href="#anchor5">&uarr;</a>: <a href="http://stackoverflow.com/a/1317324/562769">GCC's assembly output of an empty program on x86, win32</a>. nos, Stackoverflow.</li>
  <li><a name="ref6" href="#anchor6">&uarr;</a>: <a href="http://stackoverflow.com/a/1898896/562769">Why would one use &ldquo;movl $1, %eax&rdquo; as opposed to, say, &ldquo;movb $1, %eax&rdquo;</a>. Jason, Stackoverflow.</li>
  <li><a name="ref7" href="#anchor7">&uarr;</a>: <a href="http://en.literateprograms.org/Fibonacci_numbers_%28C%29#Recursive">Fibonacci numbers (C)</a>. Literate Programs.</li>
  <li><a name="ref8" href="#anchor8">&uarr;</a>: <a href="http://wpage.unina.it/rcanonic/didattica/ce1/docs/68000.pdf">
The 68000's Instruction Set</a>, page 27. Literate Programs.</li>
  <li><a name="ref9" href="#anchor9">&uarr;</a>: <a href="http://stackoverflow.com/questions/4003894/leal-assembler-instruction">LEAL Assembler instruction</a>. Nils Pipenbrinck, Stackoverflow</li>
  <li><a name="ref10" href="#anchor10">&uarr;</a>: <a href="http://stackoverflow.com/a/5367004/562769">What does this dollar sign mean in __asm?</a>. Zimbabao, Stackoverflow</li>
  <li><a name="ref11" href="#anchor11">&uarr;</a>: <a href="http://stackoverflow.com/a/9196757/562769">What do the dollar ($) and percentage (%) signs represent in assembly intel x86?</a>. Necrolis, Stackoverflow</li>
</ol>
