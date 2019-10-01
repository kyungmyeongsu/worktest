# ASP 공부용

## ASP 기초
- asp의 문장 마지막에는 세미콜론을 찍기 않는다.
- asp 구문의 처음과 끝에 스크립틀릿(<% %>)을 명시해줄것!

## 변수 선언
```
dim 변수이름
```
변수이름 앞에 dim을 입력후 사용

## 문자 출력
```
<% response.write("hello world!!!") %>
<%
= hello world!!! 
%>
```
두가지 방법으로 문자들을 출력을 할 수 있다.

## 조건문
### IF...Then
```
If 조건문 Then 실행구문
If 조건문 Then
　　실행구문 1
　　실행구문 2
End If
```
조건문을 확인 한 후에 실행 구문을 실행 시킨다.

### IF...Then...Else
```
If 조건문 1 Then
　　[실행구문 1]
[ElseIf 조건문 2 Then
　　[실행구문 2]] ...
[Else
　　[실행구문 n]]
End If
```
조건문이 참이면 해당 실행 구문을 실행한다. 조건이 맞지 않는다면 다른 조건을 확인하고 모든 조건이 아니면 else이 후의 구문을 실행한다.

### Select Case
```
Select Case 조건식
　　[Case 목록1
　　　　[실행구문-1]]
　　[Case 목록2
　　　　[실행구문-2]]

　　...

　　[Case Else
　　　　[실행구문-n]]
End Select
```
조건식을 비교하고 조건식에 나온 값을 해당 case 목록과 비교 후 존재하는 값의 실행구문을 실행한다. 모두 맞지 않는다면 case else의 실행구문을 사용한다.

## 루프
### For...Next
```
For 카운터 = 시작값 To 종료값 [Step 증가값]
　　실행구문
　　...
Next
```
우선 카운터 변수를 하나 설정한다. 이 변수를 기준으로 시작하여 증가값에 따라 변수에 증가한다. 증가한 변수가 종료값과 같아지면 반복을 끝낸다.

### For Each... Next
```
For Each 요소 In 그룹
　　실행구문
　　...
Next
```
```
ex)
<%
Dim Language(5)

Language(0) = "ASP"
Language(1) = "PHP"
Language(2) = "Visual Basic"
Language(3) = "Delphi"
Language(4) = "Power Builder"

For Each Item In Language
　　Response.Write Item & "<BR>"
Next

-결과-
ASP
PHP
Visual Basic
Delphi
Power Builder
%>
```

### Do... Loop
```
Do While 조건문
　　실행구문
　　...
Loop
```
조건이 참이라면 실행구문이 실행된다. 조건이 맞지 않다면 실행구문을 실행하지 않는다.

### Do... Loop While
```
Do
　　실행구문
　　...
Loop While 조건문
```
조건이 참이라면 실행 구문을 계속 실행 시킨다. 단, 조건이 맞지 않더라도 한 번은 실행구문을 실행 시킨다.

## 형 변환
| 문법 | 변환 |
|:---:|:---:|
|cint|integer|
|clng|long|
|cstr|string|
|csng|single|
|cdate|date|
|cbool|boolean|
|cbyte|byte|
|ccur|currency|
|cdbl|double|
|cvar|variant|
|cverr|Error|

## 대소문자 변환
```
strTest = "I Love You"
strUpper = UCASE (strTest) // 대문자로 변환 (I LOVE YOU)
strLower = LCASE (strTest) // 소문자로 변환 (i love you)
```
문자열의 내용을 대문자, 소문자로 변환 시켜준다.

## 길이 반환
```
strTest = "I Love You..."
intLength = LEN (strTest) // 문자의 길이를 반환 (13)
```
문자열의 길이를 반환한다. 공백의 문자열이 입력된다면 0으로 반환한다.

## nvl, nvl2, decode, coalesce
```
ex) SELECT NVL(COL, VAL1) FROM TABLE
- COL값이 null 이면 VAL1을, 아니면 COL값을 리턴

ex) SELECT NVL2(COL, VAL1, VAL2) FROM TABLE
- COL값이 null이 아니면 VAL1을, 아니면 VAL2값을 리턴

ex) SELECT DECODE(COL, CMP1, VAL1, CMP2, VAL2, VAL3 ) FROM TABLE
- if-else구조라고 생각하자
- COL이 CMP1이면, VAL1, CMP2이면 VAL2, 그것도 아니라면, VAL3를 리턴

ex) SELECT COALESCE(COL, VAL1, VAL2) FROM TABLE
- COL이 null이면 VAL1, VAL1도 null이라면 VAL2리던

```