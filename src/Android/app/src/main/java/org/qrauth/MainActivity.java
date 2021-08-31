package org.qrauth;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import android.Manifest;
import android.net.LinkProperties;
import android.net.Network;
import android.net.NetworkCapabilities;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.SurfaceView;
import android.widget.LinearLayout;

import fr.billet.vincent.libnet.IMobileNet;
import fr.billet.vincent.libnet.IWebSocketClientApplication;
import fr.billet.vincent.libnet.WebSocketServerConnection;
import fr.billet.vincent.libnet.networkTools;
import fr.billet.vincent.security.IPermissions;
import fr.billet.vincent.security.permissionChecker;
import fr.billet.vincent.vision.IQRScan;
import fr.billet.vincent.vision.qrScan;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;
import okhttp3.WebSocket;

public class MainActivity extends AppCompatActivity implements IPermissions, IMobileNet, IQRScan, IWebSocketClientApplication {
    // IPermissions
    private permissionChecker permissions;
    // IMobileNet
    private networkTools netTools=null;
    // IQRScan
    private SurfaceView qrView;
    private qrScan qrScanner;
    private String qrcode;
    // IWebSocketServerConnection
    private OkHttpClient client;
    private WebSocket socket;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        permissions = new permissionChecker(this);
        permissions.addPermission(Manifest.permission.CAMERA);
        permissions.addPermission(Manifest.permission.READ_PHONE_STATE);
        permissions.addPermission(Manifest.permission.RECORD_AUDIO);
    }
    //********* Mobile Application
    @Override
    protected void onStart(){
        super.onStart();
        permissions.checkPermissions();
    }
    @Override
    protected void onResume(){
        super.onResume();
        startScan();
    }
    @Override
    protected void onDestroy(){
        super.onDestroy();
    }

    //********* IPermissions
    @Override
    public void onRequestPermissionsResult(int requestCode,String[] perms, int[] grantResults) {
        permissions.onRequestPermissionsResult(requestCode,perms,grantResults);
    }
    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public void onPermissionsChecked() {
        netTools = new networkTools(this,this.getApplicationContext(), networkTools.NEED_MOBILE);
        netTools.disableWifi();
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public void onPermissionsRefused() {
        exit();
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    private void exit(){
        if (netTools!=null) { netTools.finishNet(); }
        finishAndRemoveTask();
    }
    //********* IMobileNet
    @Override
    public void onMobileNetReady(Network net) {
        Log.i("QRAuth","onMobileNetReady Available");
        startWebSocketClient();
    }
    @Override
    public void onMobileUnavailable() { }
    @Override
    public void onMobileLost(Network net) { }
    @Override
    public void onMobileBlockedStatusChanged(Network net, boolean blocked) { }
    @Override
    public void onMobileCapabilitiesChanged(Network net, NetworkCapabilities capabilities) { }
    @Override
    public void onMobileLosing(Network net, int maxMsToLive) { }
    @Override
    public void onMobileLinkPropertiesChanged(Network net, LinkProperties linkProperties) { }

    //********* IQRScan
    @Override
    public void onScan(String code) {
        Log.i("QRAuth","******"+code);
        qrcode = code;
        client = new OkHttpClient();
        startWebSocketClient();
    }
    public void startScan(){
        LinearLayout layout = (LinearLayout)findViewById(R.id.layout);
        if (qrView!=null) {
            layout.removeView(qrView);
            qrView = null;
            qrScanner = null;
        }
        qrView = new SurfaceView(this);
        layout.addView(qrView);
        qrScanner = new qrScan(this,this.getApplicationContext(),qrView);
        qrScanner.scan();
    }

    // IWebSocketClientApplication
    private void startWebSocketClient() {
        Log.i("QRAuth","Start Web Socket");
        Request request = new Request.Builder().url("ws://78.241.176.139:21089").build();
        WebSocketServerConnection listener = new WebSocketServerConnection(this);
        socket = client.newWebSocket(request, listener);

        //client.dispatcher().executorService().shutdown();
    }
    @Override
    public void onOpen(String response) {
        Log.i("QRAuth",response);
        socket.send("{\"mobile\":\""+qrcode+"\"}");
    }

    @Override
    public void onMessage(String text) {
        Log.i("QRAuth",text);
    }

    @Override
    public void onClose(int code, String reason) {

    }

    @Override
    public void onError(String message) {

    }
}
