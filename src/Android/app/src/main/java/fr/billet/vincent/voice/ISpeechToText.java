package fr.billet.vincent.voice;

public interface ISpeechToText {
    public void onSpeech(String text);
    public void onReadyForSpeech();
    public void onBeginningOfSpeech();
    public void onRmsChanged(Float rmsdB);
    public void onEndOfSpeech();
}
