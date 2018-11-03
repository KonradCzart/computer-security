import java.io.FileNotFoundException;

public class Main {

    public static void main(String[] args) {

        try {
            Decoder decoder = new Decoder("ciphertext.txt");

            decoder.getKey();
            decoder.decryptionAllCiphertext();
            var cryptograms = decoder.getCryptograms();
            String plaintext = "Wlasnie sie dowiedzial, ze Janda to nie Malgorzata Kozuchowska Zamieszanie wokol wpisu Krystyny Jandy, w ktorym poskarzyla sie, ze nie rozpoznal jej sprzedawca w sklepie, uderzylo rykoszetem w dzialalnosc artystyczna tej wybitnej aktorki. Czuje sie oszukany jako staly bywalec Teatru Polonia ? mowi nam byly juz fan tej warszawskiej sceny. ? Bylem przekonany, ze zo teatr Malgorzaty Kozuchowskiej, w ktorym moja ulubiona aktorka gra glowne role.";
            int start = 0;
            int indexCryptograms = 7;

            decoder.getKey();
            decoder.knowPlaintextAttack(plaintext,cryptograms.get(indexCryptograms), start);
            decoder.getKey();
            decoder.decryptionAllCiphertext();


        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
    }
}
