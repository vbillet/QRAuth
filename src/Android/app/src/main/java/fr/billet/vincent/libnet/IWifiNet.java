package fr.billet.vincent.libnet;

import android.net.LinkProperties;
import android.net.Network;
import android.net.NetworkCapabilities;

public interface IWifiNet {
    public void onWifiNetReady(Network net);
    public void onWifiUnavailable();
    public void onWifiLost(Network net);
    public void onWifiBlockedStatusChanged(Network net, boolean blocked);
    public void onWifiCapabilitiesChanged(Network net, NetworkCapabilities capabilities);
    public void onWifiLosing(Network net, int maxMsToLive);
    public void onWifiLinkPropertiesChanged(Network net, LinkProperties linkProperties);

}
