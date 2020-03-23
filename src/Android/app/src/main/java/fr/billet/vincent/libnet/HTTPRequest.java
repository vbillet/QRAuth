package fr.billet.vincent.libnet;
/**
 * HTTPRequest Permet de créer des requêtes HTTP Simples
 * @author Vincent BILLET
 * @lastupdated : 2020/03/06
 */

import android.net.Network;
import android.util.Log;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;

public class HTTPRequest {
    private static String TAG = "HTTPRequest";

    public HTTPRequest(){}

    public String GET(Network net, String url){
        String result="";
        try {
            URL u = new URL(url);
            URLConnection urlcon = net.openConnection(u);
            BufferedReader br = new BufferedReader(new InputStreamReader(urlcon.getInputStream()));
            String ligne;
            while ((ligne = br.readLine()) != null)
            {
                result += ligne+"\n";
            }
        } catch (Exception e){
            Log.e(TAG,e.getMessage());
        }
        return result;
    }

}
