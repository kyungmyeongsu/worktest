# PHP공부하기

## 1. PHP시작하기
### 중복 연산자 ??
```
$a = isset($a) ? $a : 1

$a = $a ?? 1
```
로 변경할 수 있다.

## 2. SQL Injection
=> 웹 애플리케이션의 뒷단에 있는 Database에 질의하는 과정 사이에
   일반적인 값 외에 악의적인 의도를 갖는 구문을 습입하여 공격자가
   원하는 SQL 쿼리문을 실행하는 기법

- 방어 방법
1. 입력받는 특수문자를 검사하여 방어한다.
2. 오류 발생시 에러 메시지를 표시하면 안된다.
3. Statement대신  Preparestatement를 사용한다.
4. Hash function을 사용한다.

## 3. 암호화, 복호화
=> 암호화에는 두가지 방법이있다. 
   첫째, 단방향 암호화.
   둘째, 양방향 암호화.

- 단방향 암호화 : 단방향 암호화는 한번 암호화를 하면 복호화를 할 수 없는   방법을 말한다. ex) 비밀번호
- 양방향 암호화 : 양방향 암호화는 암호화 후 복호화가 가능한 것을 말한다.
  ex) 이름, 전화번호 등

## 4. PHP File Upload 관련 정보
- tmp_name : 웹서버에 임시로 저장된 파일의 위치
- name : 사용자 시스템에 있을 때의 파일 이름
- size : 파일의 바이트 크기
- type : 파일의 MINE ex) text/plain, image/gif
- error : 파일업로드 시 일어난 오류

## 5. file의 method
- basename : 파일의 기본 이름을 반환
- pathinfo : 파일의 경로 정보 반환
- getimagesize : 이미지 정보 출력
````````````````````````````````````````````````
[0] : width 값
[1] : height 값
[2] : 이미지타입 값
[3] : width, height 값
[bits] : bits 값
[channels] : channels 값
[mine] : 파일 mine-type 값
````````````````````````````````````````````````