## YouTube streaming of local camera
This part of software provides streaming video from local camera to YouTube.
#### Compile executable
source files location: *you-stream*
necessarily libraries:
- libgstreamer1.0-dev
- libgstreamer-plugins-base1.0-dev

    make
#### Insatallation
    sudo cp you-stream_serv /etc/init.d/
    sudo update-rc.d you-stream_serv defaults
#### Usage
    sudo /etc/init.d/you-stream_serv status
    sudo /etc/init.d/you-stream_serv start
    sudo /etc/init.d/you-stream_serv stop
    sudo /etc/init.d/you-stream_serv restart