import java.io.FileNotFoundException;
import java.io.FileReader;
import java.util.*;

public class Decoder {

    private ArrayList<Ciphertext> cryptograms;
    private Map<Integer,Integer> letterFrequency;
    private ArrayList<Integer> key;

    public Decoder(String path) throws FileNotFoundException {
        cryptograms = initialCiphertext(path);
        letterFrequency = initialLetterFrequency();
        key = null;
    }

    public ArrayList<Integer> getKey(){
        if(key == null)
            key = findProbableKey();

        System.out.println(key);
        return key;
    }

    public void decryptionAllCiphertext(){
        if(key == null)
            key = findProbableKey();


        for(var cryptogram : cryptograms){
            for(int i = 0; i < cryptogram.getCiphertextLength(); i++){
                System.out.print((char) (cryptogram.getCharacterCode(i) ^ key.get(i)));
            }
            System.out.println(" ");
        }
    }

    public ArrayList<Ciphertext> getCryptograms() {
        return cryptograms;
    }

    public void knowPlaintextAttack(String plaintext, Ciphertext crptogram, int startPosition){

        if(key == null)
            key = findProbableKey();

        ArrayList<Integer> newKey = new ArrayList<>();

        for(int i = 0 ; i < startPosition; i++){
            newKey.add(key.get(i));
        }
        for(int i = startPosition; i < plaintext.length(); i++){
            int letterCode = plaintext.charAt(i);
            Integer positionKey = letterCode ^ crptogram.getCharacterCode(i);
            newKey.add(positionKey);
        }
        for(int i = plaintext.length(); i < key.size(); i++){
            newKey.add(key.get(i));
        }

        key = newKey;
    }

    private ArrayList<Integer> findProbableKey() {
        int maxLength = maxLengthCiphertext();
        Set<Integer> candidatKey = null;
        ArrayList<Integer> probableKey = new ArrayList<>();
        ArrayList<Ciphertext> activCryptograms = null;

        Integer positionKey = 32;
        int maxCount = 0;
        int count = 0;
        int likely = 0;
        int maxLikely = 0;


        for(int i = 0; i < maxLength; i++){
            candidatKey = new HashSet<>();
            activCryptograms = getActivCiphertext(i);

            for(var cryptogram : activCryptograms){
                for(var letterCode : letterFrequency.keySet()){
                    Integer likelyKey = letterCode ^ cryptogram.getCharacterCode(i);
                    if(!candidatKey.contains(likelyKey))
                        candidatKey.add(likelyKey);
                }
            }

            positionKey = 32;
            maxCount = 0;
            maxLikely = 0;

            for(var key : candidatKey){
                count = 0;
                likely = 0;
                for(var cryptogram : activCryptograms) {
                    Integer letterCode = cryptogram.getCharacterCode(i) ^ key;
                    if(letterFrequency.containsKey(letterCode)) {
                        count++;
                        likely += letterFrequency.get(letterCode);
                    }
                }
                if(count == maxCount) {
                    if (likely > maxLikely) {
                        positionKey = key;
                        maxLikely = likely;
                    }
                }
                else if(count > maxCount){
                    positionKey = key;
                    maxLikely = likely;
                    maxCount = count;
                }
            }
            probableKey.add(positionKey);

        }

        return probableKey;
    }

    private ArrayList<Ciphertext> getActivCiphertext(int length){
        var activ = new ArrayList<Ciphertext>();

        for(var cryptogram : cryptograms){
            if(cryptogram.getCiphertextLength() > length)
                activ.add(cryptogram);
        }

        return activ;
    }

    private ArrayList<Ciphertext> initialCiphertext(String path) throws FileNotFoundException {

        var cryptogramsArray = new ArrayList<Ciphertext>();
        FileReader reader = null;
        Ciphertext cryptogram = null;

        reader = new FileReader("ciphertext.txt");
        Scanner scanner = new Scanner(reader);

        while(scanner.hasNext()) {
            String line = scanner.nextLine();
            cryptogram = new Ciphertext(line);
            cryptogramsArray.add(cryptogram);
        }

        return cryptogramsArray;
    }

    private int maxLengthCiphertext(){
        int max = 0;

        for(var cryptogram : cryptograms){
            if(cryptogram.getCiphertextLength() > max)
                max = cryptogram.getCiphertextLength();
        }

        return max;
    }

    private Map<Integer, Integer> initialLetterFrequency(){

        var mapLetter = new TreeMap<Integer, Integer>();


        for (int i = 65; i < 91; i++) {
            mapLetter.put(i, 20);
        }
        for (int i = 48; i <= 57; i++) {
            mapLetter.put(i, 10);
        }

        mapLetter.put((int) ' ', 900 );
        mapLetter.put((int) 'a', 956 );
        mapLetter.put((int) 'e', 895 );
        mapLetter.put((int) 'o', 832 );
        mapLetter.put((int) 'i', 820 );
        mapLetter.put((int) 'z', 625 );
        mapLetter.put((int) 'n', 573 );
        mapLetter.put((int) 's', 479 );
        mapLetter.put((int) 'w', 451 );
        mapLetter.put((int) 'r', 443 );
        mapLetter.put((int) 'c', 438 );
        mapLetter.put((int) 't', 396 );
        mapLetter.put((int) 'y', 396 );
        mapLetter.put((int) 'l', 364 );
        mapLetter.put((int) 'k', 336 );
        mapLetter.put((int) 'd', 306 );
        mapLetter.put((int) 'p', 303 );
        mapLetter.put((int) 'm', 272 );
        mapLetter.put((int) 'j', 248 );
        mapLetter.put((int) 'u', 219 );
        mapLetter.put((int) 'b', 144 );
        mapLetter.put((int) 'g', 126 );
        mapLetter.put((int) 'h', 98 );
        mapLetter.put((int) 'f', 31 );
        mapLetter.put((int) 'x', 5 );
        mapLetter.put((int) 'v', 3 );
        mapLetter.put((int) ',', 149 );
        mapLetter.put((int) '.', 84 );
        mapLetter.put((int) '?', 22 );
        mapLetter.put((int) '!', 2 );
        mapLetter.put((int) ':', 1 );
        mapLetter.put((int) ';', 1 );
        mapLetter.put((int) '(', 1 );
        mapLetter.put((int) ')', 1 );
        mapLetter.put((int) '"', 1 );
        mapLetter.put((int) '-', 1 );
        mapLetter.put((int) '_', 1 );

        return mapLetter;
    }
}
