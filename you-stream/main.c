#include "trace_log.h"
#include <gst/gst.h>
#include <stdbool.h>
#include <signal.h>
#include <string.h>
#include <unistd.h>
#include <sys/resource.h>
#include <errno.h>

#define gst_chain "v4l2src ! video/x-raw,width=1280,height=720,framerate=15/1 ! clockoverlay shaded-background=true time-format=\"%d/%m/%Y %H:%M:%S\" ! avenc_h264_omx bitrate=5000000 gop-size=30 ! h264parse ! flvmux name=mux audiotestsrc wave=silence ! queue ! audioconvert ! voaacenc bitrate=128000 ! aacparse ! mux. mux. ! rtmpsink location=rtmp://a.rtmp.youtube.com/live2/"

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

    if(setpriority(PRIO_PROCESS, getpid(), -20))
    {
        trace_log_printf(LOG_WARNING, "increase priority error: %s", strerror(errno));
    }

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
                    GstMessage * msg = gst_bus_timed_pop_filtered(bus, 500 * 1000000L, GST_MESSAGE_ERROR | GST_MESSAGE_EOS | GST_MESSAGE_STATE_CHANGED | GST_MESSAGE_CLOCK_LOST);
                    if(msg)
                    {
                        trace_log_printf(LOG_INFO, "got message: %s from %s", gst_message_type_get_name(GST_MESSAGE_TYPE(msg)), GST_OBJECT_NAME(msg->src));
                        if(GST_MESSAGE_TYPE(msg) == GST_MESSAGE_ERROR || GST_MESSAGE_TYPE(msg) == GST_MESSAGE_EOS)
                        {
                            gst_element_set_state(pipeline, GST_STATE_NULL);
                            sleep(1);
                            gst_element_set_state(pipeline, GST_STATE_PLAYING);
                        }
                        else if (GST_MESSAGE_TYPE(msg) == GST_MESSAGE_STATE_CHANGED)
                        {
                            GstState old_state, new_state, pending_state;
                            gst_message_parse_state_changed (msg, &old_state, &new_state, &pending_state);
                            trace_log_printf(LOG_INFO, "\ttransition: %s->%s", gst_element_state_get_name(old_state), gst_element_state_get_name(new_state));
                        }
                        gst_message_unref(msg);
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
