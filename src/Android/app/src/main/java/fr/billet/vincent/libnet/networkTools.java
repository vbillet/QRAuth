package fr.billet.vincent.libnet;
/**
 * NetworkTools Permet des opérations de bases sur les réseaux d'un mobile
 * @author Vincent BILLET
 * @lastupdated : 2020/03/06
 */

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.LinkProperties;
import android.net.Network;
import android.net.NetworkCapabilities;
import android.net.NetworkInfo;
import android.net.NetworkRequest;
import android.net.wifi.WifiManager;
import android.os.Build;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import java.lang.reflect.Method;

/**
 * La class networkTools permet d'effectuer des opérations de base sur les réseaux du mobile.
 * - getMobileNetwork : Récupérer le réseau mobile
 * - getWifiNetwork : Récupérer le réseau wifi
 * - isMobileAvailable : Déterminer si le réseau mobile est disponible
 * - isWifiAvailable : Déterminer si le réseau wifi est disponible
 * - isConnectedToWifi : Déterminer si l'on est connecté au Wifi
 * - disableWifi : Désactiver le wifi
 * - enableWifi : Activier le wifi
 * Elle permet aussi de brancher par réflection de nouvelles méthodes à un objet.
 * - onMobileNetReady : Quand le réseau mobile devient disponible
 * - onMobileUnavailable : Quand le réseau mobile devient indisponible
 * - onMobileLost : Quand la connection au réseau mobile est perdue
 * - onMobileBlockedStatusChanged : Quand le status de blocage du réseau mobile change
 * - onMobileCapabilitiesChanged : Quand les capacités du réseau mobile changent
 * - onMobileLosing : Quand on commence à perdre le réseau mobile
 * - onMobileLinkPropertiesChanged : Quand les propriétés du réseau mobile changent
 * ----
 * - onWifiNetReady : Quand le réseau Wifi devient disponible
 * - onWifiUnavailable : Quand le réseau Wifi devient indisponible
 * - onWifiLost : Quand la connection au réseau Wifi est perdue
 * - onWifiBlockedStatusChanged : Quand le status de blocage du réseau Wifi change
 * - onWifiCapabilitiesChanged : Quand les capacités du réseau Wifi changent
 * - onWifiLosing : Quand on commence à perdre le Wifi mobile
 * - onWifiLinkPropertiesChanged : Quand les propriétés du réseau Wifi changent
 *
 * Utilisation :
 * AJOUTER au manifest :
 *     <uses-permission android:name="android.permission.INTERNET" />
 *     <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
 *     <uses-permission android:name="android.permission.CHANGE_NETWORK_STATE" />
 *     <uses-permission android:name="android.permission.ACCESS_WIFI_STATE"/>
 *     <uses-permission android:name="android.permission.CHANGE_WIFI_STATE"/>
 * 1) Initialisation :
 * netTools = new networkTools(ObjectInstance, context, networkTools.NEED_MOBILE |networkTools.NEED_WIFI);
 * 2) Ajouter les méthodes de réflection à une classe d'objet
 * 3) Terminer l'utilisation des réseaux :
 * netTools.finishNet();
 */
public class networkTools {
    private static Network mobileNet = null;
    private static Network wifiNet = null;
    public static int NEED_MOBILE = 1;
    public static int NEED_WIFI = 2;
    private Context appContext = null;
    private static Object app = null;
    private static ConnectivityManager.NetworkCallback MobileCB = null;
    private static ConnectivityManager.NetworkCallback WifiCB = null;
    private static int netUsed = 0;

    /**
     * @param obj Instance d'un objet faisant référence à une classe qui possède les méthodes de réflection networkTools.
     * @param ctx Contexte de l'application
     * @param netNeeded réseaux utilisés par l'application : networkTools.NEED_MOBILE | networkTools.NEED_WIFI
     */
    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    public networkTools(Object obj, Context ctx, int netNeeded){
        appContext = ctx.getApplicationContext();
        app = obj;
        netUsed = netNeeded;
        ConnectivityManager connMgr = (ConnectivityManager) appContext.getSystemService(Context.CONNECTIVITY_SERVICE);

        if ((netUsed & NEED_MOBILE) == NEED_MOBILE) {
            NetworkRequest.Builder reqMobile = new NetworkRequest.Builder();
            reqMobile.addCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET);
            reqMobile.addTransportType(NetworkCapabilities.TRANSPORT_CELLULAR);

            MobileCB = new ConnectivityManager.NetworkCallback() {
                /**
                 * Méthode de réflection de disponibilité du réseau mobile
                 * @param network Réseau mobile qui vient d'être disponible
                 */
                @Override
                public void onAvailable(Network network) {
                    super.onAvailable(network);
                    networkTools.mobileNet = network;
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileNetReady",Network.class);
                        m.invoke(networkTools.app,network);
                    } catch (Exception e){
                        // Nothing
                    }
                }

                /**
                 * Méthode de réflection de l'indisponibilité du réseau mobile
                 */
                @Override
                public void onUnavailable() {
                    super.onUnavailable();
                    networkTools.mobileNet = null;
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileUnavailable");
                        m.invoke(networkTools.app);
                    } catch (Exception e){
                        // Nothing
                    }
                }

                /**
                 * Méthode de réflection de la perte du réseau mobile
                 * @param network Réseau mobile qui vient d'être perdu
                 */
                @Override
                public void onLost(Network network) {
                    super.onLost(network);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileLost",Network.class);
                        m.invoke(networkTools.app,network);
                    } catch (Exception e){
                        // Nothing
                    }
                }

                /**
                 * Méthode de réflection du changement de status de blocage du réseau
                 * @param network Réseau mobile
                 * @param blocked Nouveau status de blocage
                 */
                @Override
                public void onBlockedStatusChanged(Network network, boolean blocked) {
                    super.onBlockedStatusChanged(network, blocked);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileBlockedStatusChanged",Network.class,boolean.class);
                        m.invoke(networkTools.app,network,blocked);
                    } catch (Exception e){
                        // Nothing
                    }
                }

                /**
                 * Méthode de réflection du changement de capacité réseau
                 * @param network Réseau mobile
                 * @param networkCapabilities nouvelle capacité du réseau
                 * @url https://developer.android.com/reference/android/net/NetworkCapabilities#hasCapability(int)
                 *  NET_CAPABILITY_MMS, NET_CAPABILITY_SUPL, NET_CAPABILITY_DUN, NET_CAPABILITY_FOTA, NET_CAPABILITY_IMS,
                 *  NET_CAPABILITY_CBS, NET_CAPABILITY_WIFI_P2P, NET_CAPABILITY_IA, NET_CAPABILITY_RCS,
                 *  NET_CAPABILITY_XCAP, NET_CAPABILITY_EIMS, NET_CAPABILITY_NOT_METERED, NET_CAPABILITY_INTERNET,
                 *  NET_CAPABILITY_NOT_RESTRICTED, NET_CAPABILITY_TRUSTED, NET_CAPABILITY_NOT_VPN, NET_CAPABILITY_VALIDATED,
                 *  NET_CAPABILITY_CAPTIVE_PORTAL, NET_CAPABILITY_NOT_ROAMING, NET_CAPABILITY_FOREGROUND,
                 *  NET_CAPABILITY_NOT_CONGESTED, NET_CAPABILITY_NOT_SUSPENDED,
                 *  android.net.NetworkCapabilities.NET_CAPABILITY_OEM_PAID, NET_CAPABILITY_MCX, or
                 *  android.net.NetworkCapabilities.NET_CAPABILITY_PARTIAL_CONNECTIVITY
                 */
                @Override
                public void onCapabilitiesChanged(Network network, NetworkCapabilities networkCapabilities){
                    super.onCapabilitiesChanged(network,networkCapabilities);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileCapabilitiesChanged",Network.class,NetworkCapabilities.class);
                        m.invoke(networkTools.app,network,networkCapabilities);
                    } catch (Exception e){
                        // Nothing
                    }
                }

                /**
                 * Méthode de réflection de détection de perte du réseau (On est en train de perdre le réseau)
                 * @param network Réseau mobile
                 * @param maxMsToLive estimation du nombre de milliseconde avant la perte du réseau ?
                 */
                @Override
                public void onLosing(Network network, int maxMsToLive){
                    super.onLosing(network,maxMsToLive);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileLosing",Network.class,int.class);
                        m.invoke(networkTools.app,network,maxMsToLive);
                    } catch (Exception e){
                        // Nothing
                    }
                }

                /**
                 * Méthode de réflection du changement des propriétés réseau
                 * @param network Réseau mobile
                 * @param linkProperties Nouvelles Propriétés du réseau
                 * @url https://developer.android.com/reference/android/net/LinkProperties
                 */
                @Override
                public void onLinkPropertiesChanged(Network network, LinkProperties linkProperties){
                    super.onLinkPropertiesChanged(network,linkProperties);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onMobileLinkPropertiesChanged",Network.class,LinkProperties.class);
                        m.invoke(networkTools.app,network,linkProperties);
                    } catch (Exception e){
                        // Nothing
                    }
                }
            };
            connMgr.requestNetwork(reqMobile.build(),MobileCB);
        }
        if ((netUsed & NEED_WIFI) == NEED_WIFI) {
            NetworkRequest.Builder reqWifi = new NetworkRequest.Builder();
            reqWifi.addCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET);
            reqWifi.addTransportType(NetworkCapabilities.TRANSPORT_CELLULAR);

            WifiCB = new ConnectivityManager.NetworkCallback() {
                /**
                 * Méthode de réflection de disponibilité du réseau wifi
                 * @param network Réseau wifi qui vient d'être disponible
                 */
                @Override
                public void onAvailable(Network network) {
                    networkTools.wifiNet = network;
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiNetReady",Network.class);
                        m.invoke(networkTools.app,network);
                    } catch (Exception e){
                        // Nothing
                    }
                }
                /**
                 * Méthode de réflection de l'indisponibilité du réseau wifi
                 */
                @Override
                public void onUnavailable() {
                    super.onUnavailable();
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiUnavailable");
                        m.invoke(networkTools.app);
                    } catch (Exception e){
                        // Nothing
                    }
                    networkTools.wifiNet = null;
                }
                /**
                 * Méthode de réflection de la perte du réseau wifi
                 * @param network Réseau wifi qui vient d'être perdu
                 */
                @Override
                public void onLost(Network network) {
                    super.onLost(network);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiLost",Network.class);
                        m.invoke(networkTools.app,network);
                    } catch (Exception e){
                        // Nothing
                    }
                    networkTools.wifiNet = null;
                }
                /**
                 * Méthode de réflection du changement de status de blocage du réseau
                 * @param network Réseau wifi
                 * @param blocked Nouveau status de blocage
                 */
                @Override
                public void onBlockedStatusChanged(Network network, boolean blocked) {
                    super.onBlockedStatusChanged(network, blocked);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiBlockedStatusChanged",Network.class,boolean.class);
                        m.invoke(networkTools.app,network,blocked);
                    } catch (Exception e){
                        // Nothing
                    }
                }
                /**
                 * Méthode de réflection du changement de capacité réseau
                 * @param network Réseau wifi
                 * @param networkCapabilities nouvelle capacité du réseau
                 * @url https://developer.android.com/reference/android/net/NetworkCapabilities#hasCapability(int)
                 *  NET_CAPABILITY_MMS, NET_CAPABILITY_SUPL, NET_CAPABILITY_DUN, NET_CAPABILITY_FOTA, NET_CAPABILITY_IMS,
                 *  NET_CAPABILITY_CBS, NET_CAPABILITY_WIFI_P2P, NET_CAPABILITY_IA, NET_CAPABILITY_RCS,
                 *  NET_CAPABILITY_XCAP, NET_CAPABILITY_EIMS, NET_CAPABILITY_NOT_METERED, NET_CAPABILITY_INTERNET,
                 *  NET_CAPABILITY_NOT_RESTRICTED, NET_CAPABILITY_TRUSTED, NET_CAPABILITY_NOT_VPN, NET_CAPABILITY_VALIDATED,
                 *  NET_CAPABILITY_CAPTIVE_PORTAL, NET_CAPABILITY_NOT_ROAMING, NET_CAPABILITY_FOREGROUND,
                 *  NET_CAPABILITY_NOT_CONGESTED, NET_CAPABILITY_NOT_SUSPENDED,
                 *  android.net.NetworkCapabilities.NET_CAPABILITY_OEM_PAID, NET_CAPABILITY_MCX, or
                 *  android.net.NetworkCapabilities.NET_CAPABILITY_PARTIAL_CONNECTIVITY
                 */
                @Override
                public void onCapabilitiesChanged(Network network, NetworkCapabilities networkCapabilities){
                    super.onCapabilitiesChanged(network,networkCapabilities);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiCapabilitiesChanged",Network.class,NetworkCapabilities.class);
                        m.invoke(networkTools.app,network,networkCapabilities);
                    } catch (Exception e){
                        // Nothing
                    }
                }
                /**
                 * Méthode de réflection de détection de perte du réseau (On est en train de perdre le réseau)
                 * @param network Réseau wifi
                 * @param maxMsToLive estimation du nombre de milliseconde avant la perte du réseau ?
                 */
                @Override
                public void onLosing(Network network, int maxMsToLive){
                    super.onLosing(network,maxMsToLive);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiLosing",Network.class,int.class);
                        m.invoke(networkTools.app,network,maxMsToLive);
                    } catch (Exception e){
                        // Nothing
                    }
                }
                /**
                 * Méthode de réflection du changement des propriétés réseau
                 * @param network Réseau wifi
                 * @param linkProperties Nouvelles Propriétés du réseau
                 * @url https://developer.android.com/reference/android/net/LinkProperties
                 */
                @Override
                public void onLinkPropertiesChanged(Network network, LinkProperties linkProperties){
                    super.onLinkPropertiesChanged(network,linkProperties);
                    try{
                        Method m = networkTools.app.getClass().getMethod("onWifiLinkPropertiesChanged",Network.class,LinkProperties.class);
                        m.invoke(networkTools.app,network,linkProperties);
                    } catch (Exception e){
                        // Nothing
                    }
                }
            };
            connMgr.requestNetwork(reqWifi.build(),WifiCB);
        }
    }

    /**
     * Permet trouver l'objet network du réseau mobile
     * @return network Réseau mobile
     */
    public static Network getMobileNetwork(){ return networkTools.mobileNet; }

    /**
     * Permet de trouver l'objet Network du réseau wifi
     * @return Network réseau Wifi
     */
    public static Network getWifiNetwork(){ return networkTools.wifiNet; }

    /**
     * permet de savoir si le réseau mobile est disponible
     * @return boolean true si le réseau mobile est disponible
     */
    public boolean isMobileNetAvailable() { return networkTools.mobileNet!= null; }

    /**
     * permet de savoir si le réseau wifi est disponible
     * @return boolean true si le réseau wifi est disponible
     */
    public boolean isWifiNetAvailable() { return networkTools.wifiNet!= null; }

    /**
     * permet de savoir si le réseau wifi est connecté
     * @return boolean true si le réseau wifi est connecté
     */
    public boolean isConnectedToWifi() {
        ConnectivityManager connectivityManager = (ConnectivityManager) appContext.getSystemService(Context.CONNECTIVITY_SERVICE);
        if (connectivityManager == null) {  return false; }
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.M) {
            Network network = connectivityManager.getActiveNetwork();
            NetworkCapabilities capabilities = connectivityManager.getNetworkCapabilities(network);
            if (capabilities == null) {
                return false;
            }
            return capabilities.hasTransport(NetworkCapabilities.TRANSPORT_WIFI);
        } else {
            NetworkInfo networkInfo = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
            if (networkInfo == null) {
                return false;
            }
            return networkInfo.isConnected();
        }
    }

    /**
     * Désactive le wifi
     */
    public void disableWifi(){
        WifiManager wifi = (WifiManager) appContext.getApplicationContext().getSystemService(Context.WIFI_SERVICE);
        if (wifi != null) {
            wifi.setWifiEnabled(false);
        }
    }

    /**
     * Active le wifi
     */
    public void enableWifi(){
        WifiManager wifi = (WifiManager) appContext.getApplicationContext().getSystemService(Context.WIFI_SERVICE);
        if (wifi != null) {
            wifi.setWifiEnabled(true);
        }
    }

    /**
     * Termine l'utilisation des réseaux par networkTools.
     */
    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    public void finishNet() {
        ConnectivityManager connMgr = (ConnectivityManager) appContext.getSystemService(Context.CONNECTIVITY_SERVICE);

        if ((netUsed & NEED_MOBILE) == NEED_MOBILE) {
            connMgr.unregisterNetworkCallback(MobileCB);
        }
        if ((netUsed & NEED_WIFI) == NEED_WIFI) {
            connMgr.unregisterNetworkCallback(WifiCB);
        }
    }
}
