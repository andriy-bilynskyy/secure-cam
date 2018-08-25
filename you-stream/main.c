#include "trace_log.h"
#include <gst/gst.h>
#include <stdbool.h>
#include <signal.h>
#include <string.h>
#include <unistd.h>

#define gst_chain "v4l2src ! video/x-raw,width=1280,height=720,framerate=15/1 ! clockoverlay shaded-background=true time-format=\"%d/%m/%Y %H:%M:%S\" ! avenc_h264_omx bitrate=5000000 ! h264parse ! flvmux name=mux audiotestsrc wave=silence ! voaacenc bitrate=128000 ! mux. mux. ! rtmpsink location=rtmp://a.rtmp.youtube.com/live2/"

volatile static bool terminate = false;

void sig_handler(int signum)
{
    trace_log_printf(LOG_INFO, "terminating...");
    terminate = true;
}

int main(int argc, char *argv[])
{
    trace_log_setlevel(LOG_INFO);
    trace_log_printf(LOG_INFO, "application started");

    struct sigaction act;
    memset(&act, 0, sizeof(act));
    act.sa_handler = sig_handler;
    sigemptyset(&act.sa_mask);
    (void)sigaction(SIGINT, &act, NULL);

    signal(SIGPIPE, SIG_IGN);

    gst_init(&argc, &argv);
    if(gst_is_initialized())
    {
        trace_log_printf(LOG_INFO, "gst chain: %s", gst_chain KEY);
        GError * err = NULL;
        GstElement * pipeline = gst_parse_launch(gst_chain KEY, &err);
        if(pipeline && !err)
        {
            trace_log_printf(LOG_INFO, "gst pipeline created");
            gst_element_set_state(pipeline, GST_STATE_PLAYING);
            GstBus * bus = gst_element_get_bus(pipeline);
            if(bus)
            {
                while(!terminate)
                {
                    
                    GstMessage * msg = gst_bus_timed_pop_filtered(bus, 500 * 1000000L, GST_MESSAGE_ERROR | GST_MESSAGE_EOS);
                    if(msg)
                    {
                        trace_log_printf(LOG_INFO, "got message: %s", gst_message_type_get_name(GST_MESSAGE_TYPE(msg)));
                        gst_message_unref(msg);
                        gst_element_set_state(pipeline, GST_STATE_NULL);
                        sleep(1);
                        gst_element_set_state(pipeline, GST_STATE_PLAYING);
                    }
                }
                gst_object_unref(bus);
            }
            else
            {
                trace_log_printf(LOG_ERROR, "gst get bus error");
            }
            trace_log_printf(LOG_INFO, "terminated");
        }
        else
        {
            trace_log_printf(LOG_ERROR, "gst create pipeline error: %s", err ? err->message : "N/A");
        }
        if(pipeline)
        {            
            gst_element_set_state(pipeline, GST_STATE_NULL);
            gst_object_unref(pipeline);
        }
        gst_deinit();
    }
    else
    {
        trace_log_printf(LOG_ERROR, "gst init error");
    }

    trace_log_printf(LOG_INFO, "application stopped");
    return 0;
}
