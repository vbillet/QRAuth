package fr.billet.vincent.libnet;

import android.util.Log;

import java.lang.reflect.Method;

import okhttp3.Response;
import okhttp3.WebSocket;
import okhttp3.WebSocketListener;
import okio.ByteString;

public class WebSocketServerConnection extends WebSocketListener {
    private static final int NORMAL_CLOSURE_STATUS = 1000;
    private Object app;
    public WebSocketServerConnection(Object obj){
        app=obj;
    }
    @Override
    public void onOpen(WebSocket webSocket, Response response){
        try{
            Method m = app.getClass().getMethod("onOpen",String.class);
            m.invoke(app,response.message());
        } catch (Exception e){
            // Nothing
        }
    }
    @Override
    public void onMessage(WebSocket webSocket,String text){
        try{
            Method m = app.getClass().getMethod("onMessage",String.class);
            m.invoke(app,text);
        } catch (Exception e){
            // Nothing
        }
    }
    @Override
    public void onMessage(WebSocket webSocket, ByteString bytes){
        Log.i("QRAuth","onMessage 2 "+bytes.toString());
    }
    @Override
    public void onClosing(WebSocket webSocket,int code, String reason){
        Log.i("QRAuth","onClose "+reason);
    }
    @Override
    public void onFailure(WebSocket webSocket,Throwable t, Response response){
        Log.i("QRAuth","onFailure "+t.getMessage());
    }
}
