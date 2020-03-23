package fr.billet.vincent.libnet;

import android.net.LinkProperties;
import android.net.Network;
import android.net.NetworkCapabilities;

public interface IMobileNet {
    public void onMobileNetReady(Network net);
    public void onMobileUnavailable();
    public void onMobileLost(Network net);
    public void onMobileBlockedStatusChanged(Network net, boolean blocked);
    public void onMobileCapabilitiesChanged(Network net, NetworkCapabilities capabilities);
    public void onMobileLosing(Network net, int maxMsToLive);
    public void onMobileLinkPropertiesChanged(Network net, LinkProperties linkProperties);
}
