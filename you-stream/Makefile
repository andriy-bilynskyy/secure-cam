PROJECT_NAME     := you-stream

SRC_FILES += \
  main.c \
  trace_log.c \

INC_FOLDERS += \
  ./ \
  /usr/include/gstreamer-1.0 \
  /usr/include/glib-2.0 \
  /usr/lib/arm-linux-gnueabihf/glib-2.0/include \

LIB_FILES += \
  gstreamer-1.0 \
  gobject-2.0 \
  glib-2.0 \

CFLAGS += -O0
CFLAGS += -g3
CFLAGS += -Wall
CFLAGS += -c
CFLAGS += -fmessage-length=0
CFLAGS += -DKEY=\"${Y_KEY}\"

INST_PATH = /usr/local/bin/

OBJ_FILES = $(SRC_FILES:.c=.o)

default: all

clean:
	rm -f $(PROJECT_NAME)
	rm -f $(OBJ_FILES)

all: build

install: $(PROJECT_NAME)
	cp $(PROJECT_NAME) $(INST_PATH)

uninstall:
	rm -f $(INST_PATH)$(PROJECT_NAME)

ifdef Y_KEY
build: $(PROJECT_NAME)
else
build:
	$(error Y_KEY is undefined. use: make Y_KEY=<your youtube stream key>)
endif
 
$(PROJECT_NAME): $(OBJ_FILES)
	gcc $(OBJ_FILES) -o $@ $(addprefix -l, $(LIB_FILES))


%.o:%.c
	gcc $(CFLAGS) $(CFLAGS) $(addprefix -I, $(INC_FOLDERS)) $< -o $@
