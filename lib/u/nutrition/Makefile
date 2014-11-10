include ../../../../config.mk
install = install

php = $(wildcard *.php)

SUBDIRS := $(dir $(wildcard */Makefile))

default clean install::
	@for d in $(SUBDIRS); \
	do $(MAKE) -C $$d $@; \
	done

install:: install-local

install-local::
	mkdir -p ../../../../html/lib/u/nutrition
	$(install) $(php) ../../../../html/lib/u/nutrition/
