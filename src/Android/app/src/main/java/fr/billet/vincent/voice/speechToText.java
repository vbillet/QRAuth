package fr.billet.vincent.voice;

import android.content.Intent;
import android.os.Bundle;
import android.speech.RecognitionListener;
import android.speech.RecognizerIntent;
import android.speech.SpeechRecognizer;
import android.util.Log;

import androidx.appcompat.app.AppCompatActivity;

import java.lang.reflect.Method;
import java.util.ArrayList;

public class speechToText implements RecognitionListener {
    private SpeechRecognizer speech = null;
    private Intent recognizerIntent;
    private AppCompatActivity app;
    public speechToText(AppCompatActivity act){
        app = act;
    }
    public void startRecognition() {
        if (speech != null) {
            try{ speech.destroy(); } catch(Exception e) { }
        }
        speech = SpeechRecognizer.createSpeechRecognizer(app);
        speech.setRecognitionListener(this);
        recognizerIntent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        recognizerIntent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_PREFERENCE,"fr");
        recognizerIntent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        recognizerIntent.putExtra(RecognizerIntent.EXTRA_MAX_RESULTS, 3);
        recognizerIntent.putExtra(RecognizerIntent.EXTRA_PREFER_OFFLINE,true);

    }
    public void stopRecognition(){
        if (speech != null) {
            speech.destroy();
            speech=null;
        }
    }
    @Override
    public void onReadyForSpeech(Bundle params) {
        try{
            Method m = app.getClass().getMethod("onReadyForSpeech");
            m.invoke(app);
        } catch (Exception e){
            // Nothing
        }
    }

    @Override
    public void onBeginningOfSpeech() {
        try{
            Method m = app.getClass().getMethod("onBeginningOfSpeech");
            m.invoke(app);
        } catch (Exception e){
            // Nothing
        }
    }

    @Override
    public void onRmsChanged(float rmsdB) {
        try{
            Method m = app.getClass().getMethod("onRmsChanged",Float.class);
            m.invoke(app,(Float)rmsdB);
        } catch (Exception e){
            // Nothing
        }
    }

    @Override
    public void onBufferReceived(byte[] buffer) { }

    @Override
    public void onEndOfSpeech() {
        try{
            Method m = app.getClass().getMethod("onEndOfSpeech");
            m.invoke(app);
        } catch (Exception e){
            // Nothing
        }
    }

    @Override
    public void onError(int error) {
        String errorMessage = getErrorText(error);
        Log.e("SpeechToText",errorMessage);
        startRecognition();
    }

    @Override
    public void onResults(Bundle results) {
        ArrayList<String> matches = results.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION);
        String text = "";
        for (String result : matches)
            text += result + "\n";
        try{
            Method m = app.getClass().getMethod("onSpeech",String.class);
            m.invoke(app,text);
        } catch (Exception e){
            // Nothing
        }
        startRecognition();
    }

    @Override
    public void onPartialResults(Bundle partialResults) { }

    @Override
    public void onEvent(int eventType, Bundle params) { }

    public static String getErrorText(int errorCode) {
        String message;
        switch (errorCode) {
            case SpeechRecognizer.ERROR_AUDIO:
                message = "Audio recording error";
                break;
            case SpeechRecognizer.ERROR_CLIENT:
                message = "Client side error";
                break;
            case SpeechRecognizer.ERROR_INSUFFICIENT_PERMISSIONS:
                message = "Insufficient permissions";
                break;
            case SpeechRecognizer.ERROR_NETWORK:
                message = "Network error";
                break;
            case SpeechRecognizer.ERROR_NETWORK_TIMEOUT:
                message = "Network timeout";
                break;
            case SpeechRecognizer.ERROR_NO_MATCH:
                message = "No match";
                break;
            case SpeechRecognizer.ERROR_RECOGNIZER_BUSY:
                message = "RecognitionService busy";
                break;
            case SpeechRecognizer.ERROR_SERVER:
                message = "error from server";
                break;
            case SpeechRecognizer.ERROR_SPEECH_TIMEOUT:
                message = "No speech input";
                break;
            default:
                message = "Didn't understand, please try again.";
                break;
        }
        return message;
    }

}
