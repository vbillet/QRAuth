package fr.billet.vincent.libnet;

import okhttp3.Response;
import okhttp3.WebSocket;

public interface IWebSocketClientApplication {
    public void onOpen(String response);
    public void onMessage(String text);
    public void onClose(int code, String reason);
    public void onError(String message);
}
